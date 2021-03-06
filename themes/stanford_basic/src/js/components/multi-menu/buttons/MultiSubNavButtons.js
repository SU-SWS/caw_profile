import SecondarySubNavButtons from '../../secondary-nav/buttons/SecondarySubNavButtons';
import OnArrowRightToggleLV1 from './events/OnArrowRightToggleLV1';
import OnArrowLeftLV1 from './events/OnArrowLeftLV1';
import OnArrowRightLV1 from './events/OnArrowRightLV1';
import OnArrowDownToggleLV1 from './events/OnArrowDownToggleLV1';
import OnClickToggleLV1 from './events/OnClickToggleLV1';
import OnArrowUpToggleLV1 from './events/OnArrowUpToggleLV1';
import OnEsc from '../../secondary-nav/common/events/OnEsc.js';


/**
 * SecondarySubNavAccordion Class
 *
 * A sub menu class for creating a menu with accordion functionality.
 */
export default class MultiSubNavButtons extends SecondarySubNavButtons {

  /**
   * Creates an event registry for handling types of events.
   *
   * This registry is used by the EventHandlerDispatch class to bind and
   * execute the events in the created property key.
   *
   * @param  {Object} options Options to merge in with the defaults.
   *
   * @return {Object} A key/value registry of events and handlers.
   */
  createEventRegistry(options) {

    var registryDefaults = super.createEventRegistry({});
    // If we are the first level (top) we need to adjust for mobile vs desktop.
    if (this.getDepth() === 1) {
      registryDefaults = Object.assign(registryDefaults, {
        onKeydownArrowLeft: OnArrowLeftLV1,
        onKeydownArrowRight: OnArrowRightLV1
      });
    }

    return registryDefaults;
  }

  /**
   * Initialize the toggle button.
   */
   initToggleButton() {
     var options = {};
     // Overrides for level 1 desktop.
     if (this.getDepth() === 1) {
       options.eventRegistry = {
         onKeydownArrowRight: OnArrowRightToggleLV1,
         onKeydownArrowDown: OnArrowDownToggleLV1,
         onKeydownArrowUp: OnArrowUpToggleLV1,
         onClick: OnClickToggleLV1,
         onKeydownEscape: OnEsc
       };
     }

     // Do eet.
     super.initToggleButton(options);
   }

}
