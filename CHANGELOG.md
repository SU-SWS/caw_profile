# Cardinal at Work Profile

10.0.0
--------------------------------------------------------------------------------
_Release Date: 2023-10-20_

- All things Drupal 10
- New prosthetic field for benefits.

9.3.3
--------------------------------------------------------------------------------
_Release Date: 2023-09-22_

- Replace layout builder block with custom markup block.

9.3.2
--------------------------------------------------------------------------------
_Release Date: 2023-09-21_

- Added "Benefit Perks" field.

9.3.1
--------------------------------------------------------------------------------
_Release Date: 2023-09-20_

- Fixed saml role settings.

9.2.6
--------------------------------------------------------------------------------
_Release Date: 2023-08-24_

- D8CORE-6786: Updating to the new font through a Decanter update (#695)
- Added and configured Oembed lazyload for video media
- D8CORE-4495 | Update past events text on node view
- Updated publication importer for layout paragraphs instead of react paragraphs

9.2.5
--------------------------------------------------------------------------------
_Release Date: 2023-07-26_

- Add system check test (#691)
- Update decoupled menu to recognize "Expanded" menu setting
- D8CORE-6816 Restored permissions to run importers for site managers
- Add missing chosen library
- Updated google tag settings
- Updated email link
- Removed unused modules
- updated flat taxonomy module

9.2.4
--------------------------------------------------------------------------------
_Release Date: 2023-07-11_

- Fixed basic page subsite navigation missing.

9.2.3
--------------------------------------------------------------------------------
_Release Date: 2023-07-10_

- Updated Hyvor
- Fixed "Whole Card Link" behavior in cards.

9.2.2
--------------------------------------------------------------------------------
_Release Date: 2023-06-07_

- Retain "Changed" date when updating nodes in update hook.

9.2.1
--------------------------------------------------------------------------------
_Release Date: 2023-06-07_

- Updated display settings due to update hook in smart_trim module

9.2.0
--------------------------------------------------------------------------------
_Release Date: 2023-06-06_

- CAWD9-143 Added 2 fields to medical benefits (#118)
- Fix config and dependencies' (#119)
- try catch the theme uninstall
- Fix policy typo (#662)
- D8CORE-2622, D8CORE-4494 | Move brand bar and skip-links into header landmark banner (#665)
- D8CORE-6654 | Update stanford_basic package-lock (#667)
- Restored jsonapi path data to correct typescript issues
- adjusted jsonapi for config pages
- Updated page cache query ignore settings for jsonapi views
- Enabled view jsonapi endpoint
- Added tiny base64 blur image to json api data
- Updated json api extras config
- D8CORE-6336: changing hover on print icon for policy CT (#661)
- Fixed JsonAPI endpoints for stanford page
- Updated jsonapi endpoints (#664)
- Fixed bug in menu update hook for duplicate links
- Removed path alias changes
- Added path process manager in update hook
- Removed unused ckeditor libraries/modules
- Simplified and correct menu link upgrade path
- Fix tests (#660)
- Added patch for menu_link to add expanded option
- Migrate everything to layout paragraphs and upgrade all modules/themes (#654)
- Changed the "See more celebrations" link url
- Updated permissions
- Cleanup eck dependency
- Fixup composer version
- Updated permissions to allow site managers create media taxonomy
- Replace log entity type and remove ECK (#656)
- Uninstalled autoservices
- Fix repo with latest config/composer (#117)
- Enabled empty_fields module
- DEVOPS-000: SMRT quotes R dumb (#655)
- added codeCoverageIgnoreEnd
- Exported configs after db updates
- Fixed related policies display
- Added missing drupal/jsonapi_hypermedia module
- hotfix-12-03-2022 (#651)
- excise memcache (#650)
- Removed patches that were moved to drupal-patches
- Updated configs after scheduler module update
- update editoria11y and smart_trim modules (#648)
- D8CORE-6370: Moved the authority field. (#647)
- Improved tests for configuration ignore
- Fix preprocess_breadcrumbs to prevent failure with drush
- Add and enable memcache for dev, stage, and prod (#645)
- D8CORE-2932 and D8CORE-6357: Fixed extra spacing on people list items. (#641)
- D8CORE-6348: adding summary into the related policy cards (#640)
- D8CORE-6345: Display policy change log title in the lists (#642)
- Fixed the path to the masking image for circle image style
- Added and configured "Page Cache Query Ignore" module
- D8CORE-6347 Show body summary and add help text
- D8CORE-6346 Reorder form fields and add help text
- Added and adjusted printable view mode for policies
- Prepend "Canceled" to canceled events
- D8CORE-6237: Corrected courses migration (#638)
- D8CORE-6330: moved the back and forward button for mobile (#636)
- Moved modules into consolidated repo location for stanford_profile_helper (#637)
- Added update hook to make deployment smoother
- D8CORE-6329: unique ids for prev/next buttons (#635)
- D8CORE-6327: fix to the policy related cards for mobile (#634)
- D8CORE-6323: adding styling for change log block anywhere (#633)
- Updated Test steps (#116)
- D8CORE-6325 Set the active item on book side navigation (#632)
- D8CORE-3498: Added additional contact information field for events (#631)
- D8CORE-6304: layout and style set up for Policy (#628)
- D8CORE-6251: Added a toggle in the theme to turn off the external links (#623)
- D8CORE-6304: adding the logo for the print function on policy (#625)
- Added Fast 404 module (#630)
- D8CORE-6315 Remove duplicate "All" publication menu link
- Apply chosen to related policy field
- D8CORE-6312 Fix node title not reflecting the changes (#629)
- D8CORE-5250 Add "Last Updated" to search results items (#619)
- Modify directory permissions when copying the file during install (#626)
- Added view to display policy child pages (#624)
- Revert composer.json (#622)
- D8CORE-6288 Policy content type (#617)
- Removed deprecation warnings
- D8CORE-6247 Add "Code" to WYSIWYG Style dropdown
- D8CORE-6242 D8CORE-4977 D8CORE-6055 Improve people lists and add pronoun field (#615)
- Disable confirm-leave js on CI environments
- Removed token_or patch (#618)


9.1.6
--------------------------------------------------------------------------------
_Release Date: 2022-10-10_

- Added meals and transportation medical benefits fields (#113)
- Added fast 404 module

9.1.5
--------------------------------------------------------------------------------
_Release Date: 2022-10-04_

- Updated modules, config and patches
- CAWD9-141 Properly set the comparison tool header attributes
- CAWD9-140 Simplify aria live regions
- CAWD9-139 Adjust attributes for the comparison table
- Renamed benefits offering labels

9.1.4
--------------------------------------------------------------------------------
_Release Date: 2022-09-14_

- CAWD9-138 Replaced cardinal red with lagunita background color (#109)
- D8CORE-6244: fixing float with a clear (#614)
- D8CORE-4363: Show images in cards at all breakpoint (#610)
- D8CORE-6235 Fix "Save and Import" on importer forms. (#613)
- D8CORE-6058 Adjust, improve, and add metatags for content (#608)
- D8CORE-6217 Allow configuring maximum main menu depth (#611)
- D8CORE-5825 Add taxonomy field to media types for categorization (#609)
- D8CORE-6245: fix to the transparent localist event link (#612)
- D8CORE-6224 Added localist bookmark url for importer (#607)
- D8CORE-5955: Added journal publisher field, updated publisher label. (#602)
- D8CORE-5656 D8CORE-6215 D8CORE-6048 Adjustments to people node form and views (#606

9.1.3
--------------------------------------------------------------------------------
_Release Date: 2022-08-26_

- D8CORE-6219 Adjust help text and remove aria label help text
- D8CORE-6168 Default to 12 column widths on paragraph row items
- Fixed courses field widget with view query parameter
- allow rowspan and colspan to table headers

9.1.2
--------------------------------------------------------------------------------
_Release Date: 2022-08-11_

- Fixed events importer title property.

11.0.2
-------------------------------------------------------------------------------
- Fix acceptance tests.

11.0.1
-------------------------------------------------------------------------------
- Fix site setting redirect logic

11.0.0
-------------------------------------------------------------------------------
- Use h2 heading for global message
- D8CORE-6976: updated to newest decanter (#730)
- Update config ignore settings for latest module (#729)
- Update local footer config userguide links (#727)
- Added and configured autoprefixer for css compiler
- Updated search api config
- Fixed related policies to avoid self referencing
- SDSS-1007: Add support for global footer variant. (#724)
- Added su-masthead-inner class to masthead <section>. (#725)
- D8CORE-6895: updates for new Decanter and updating webpack (#710)
- D8CORE-6951  D10 Clean up admin toolbar (#723)
- D8CORE-6952: changed to list style none (#719)
- D8CORE-6953: Additional edits to the editing buttons (#722)
- Display field copy released a D10 version
- Lifecycle management contact fields
- D8CORE-6953: fixes to local task tabs (#720)
- D8CORE-6977 Switch to use CSHS instead of simple heiracry select (#721)
- D8CORE-6984 include policy content in sitemap
- D8CORE-3718 Minor a11y issues for decoupled main menu
- Updated user mail attribute value for samlauth
- Drupal 10 Config & test updates (#705)
- D10 Install

10.1.1
-------------------------------------------------------------------------------
- Update samlauth settings when a role is created or deleted

10.1.0
-------------------------------------------------------------------------------
- Use components field instead of layout builder for list pages (#709)
- D8CORE-4551: updated permissions for contributors and site editors (#690)
- D8CORE-6843: Update bad user guide links in help text (#694)
- Fixed styles for login page when on intranets
- D8CORE-6896: changed the non discrimination link (#707)
- D8CORE-6842 Added and configured stanford_samlauth (#701)
- D8CORE-6844 Fixup mobile menu at medium breakpoint (#703)
- Update localist importer to use the localist json data parser
- D8CORE-6786: Updating to the new font through a Decanter update (#695)
- Added and configured Oembed lazyload for video media
- D8CORE-4495 Update past events text on node view
- Updated publication importer for layout paragraphs instead of react paragraphs


10.0.4
-------------------------------------------------------------------------------
- Update hook to update field storage definitions.

10.0.3
-------------------------------------------------------------------------------
- Update decoupled menu to recognize "Expanded" menu setting
- D8CORE-6816 Restored permissions to run importers for site managers
- Add missing chosen library
- Updated google tag settings

10.0.2
-------------------------------------------------------------------------------
- Added to the update hook to handle layout builder menu blocks when updating menu links.
- Added user json api endpoint.

10.0.1
--------------------------------------------------------------------------------
- add ptype classes just in case
- Remove unwanted paragraph types on publication field
- Add new layout paragraphs styles class

10.0.0
--------------------------------------------------------------------------------

- Updating to new decanter version (#682)
- CAP-67 Import profile phone number to the non-mobile phone field
- D8CORE-4539 Removed "Role" attribute on figure element
- D8CORE-6760 | Update ckeditor5 styles (#679)
- D8CORE-6629 use list elements on search result page
- D8CORE-6628 Hide `li` element for mobile search field when on desktop
- D8CORE-6368 Use list elements for related policy display
- D8CORE-5950 Fixed aspect ratio of youtube videos on event pages
- D8CORE-6633 Add skip link anchor to the search page (#681)
- D8CORE-6339 Added main content anchor link destination
- Update banner aspect ratio for small breakpoint (#680)
- D8CORE-6695, 6694 Updated spacer paragraph with optional sizes (#677)
- Fixed typo in "Experimental"
- D8CORE-6770 Change lables from "Components" to "Paragraphs"
- Added "Admin notes" to the site settings config page
- D8CORE-6457 D8CORE-6476 D8CORE-6477 Tweaks to policy content fields
- Added undo and redo buttons to ckeditor
- Move help section to be below brand bar in header (#674)
- Revert "D8CORE-4495: changing past events text (#669)" (#673)
- D8CORE-4495: changing past events text (#669)
- D8CORE-5407 | @jdwjdwjdw | A11y: Update lockup cell2 max-width, line3 line-height (#672)
- Provide a new replacement menu with a decoupled main menu (#663)
- D8CORE-6416 Update google analytics tracking for stanford_basic theme
- D8CORE-2622, D8CORE-4494 | Move brand bar and skip-links into header landmark banner (#665)
- D8CORE-6654 | Update stanford_basic package-lock (#667)
- Added tiny base64 blur image to json api data
- D8CORE-6336: changing hover on print icon for policy CT (#661)
- Updated jsonapi endpoints (#664)
- Migrate everything to layout paragraphs and upgrade all modules/themes (#654)


9.2.8
--------------------------------------------------------------------------------
- Don't replace attributes on menu link items.

9.2.7
--------------------------------------------------------------------------------
- Moved the help region below the brand bar.

9.2.6
--------------------------------------------------------------------------------
- Exported configs after db updates
- D8CORE-6416 Update google analytics tracking for stanford_basic theme
- Added better tag and release action
- Fix policy typo (#662)
- D8CORE-2622, D8CORE-4494 Move brand bar and skip-links into header landmark banner (#665)
- D8CORE-6654 Update stanford_basic package-lock (#667)
- D8CORE-6336: changing hover on print icon for policy CT (#661)
- Updated permissions to allow site managers create media taxonomy

9.2.5
--------------------------------------------------------------------------------
- Replace log entity type and remove ECK (#656)
- Enabled empty_fields module
- DEVOPS-000: SMRT quotes R dumb (#655)

9.2.4
--------------------------------------------------------------------------------
_Release Date: 2022-12-13_
- Added missing drupal/jsonapi_hypermedia module
- Fixed related policies display

9.2.3
--------------------------------------------------------------------------------
_Release Date: 2022-12-03_
- Hotfix. Fixup composer.json

9.2.2
--------------------------------------------------------------------------------
_Release Date: 2022-12-02_
- Hotfix. Remove memcache

9.2.1
--------------------------------------------------------------------------------
_Release Date: 2022-11-30_
- D8CORE-6424 Hide legacy importer fields on importer form
- D8CORE-6338 updated help text on policy changelog fields
- D8CORE-6422 Allow news to hide social share icons
- D8CORE-6370: Moved the authority field. (#647)
- Improved tests for configuration ignore
- Fix preprocess_breadcrumbs to prevent failure with drush
- Add and enable memcache for dev, stage, and prod (#645) (remove?)
- D8CORE-2932 and D8CORE-6357: Fixed extra spacing on people list items. (#641)
- D8CORE-6348: adding summary into the related policy cards (#640)
- D8CORE-6345: Display policy change log title in the lists (#642)
- Added and configured "Page Cache Query Ignore" module

9.2.0
--------------------------------------------------------------------------------
_Release Date: 2022-10-25_

- D8CORE-6347 Show body summary and add help text
- D8CORE-6346 Reorder form fields and add help text
- Added and adjusted printable view mode for policies
- Prepend "Canceled" to canceled events
- D8CORE-6237: Corrected courses migration (#638)
- D8CORE-6330: moved the back and forward button for mobile (#636)
- Moved modules into consolidated repo location for stanford_profile_helper (#637)
- Added update hook to make deployment smoother
- D8CORE-6329: unique ids for prev/next buttons (#635)
- D8CORE-6327: fix to the policy related cards for mobile (#634)
- D8CORE-6323: adding styling for change log block anywhere (#633)
- D8CORE-6325 Set the active item on book side navigation (#632)
- D8CORE-3498: Added additional contact information field for events (#631)
- D8CORE-6304: layout and style set up for Policy (#628)
- D8CORE-6251: Added a toggle in the theme to turn off the external links (#623)
- D8CORE-6304: adding the logo for the print function on policy (#625)
- Added Fast 404 module (#630)
- D8CORE-6315 Remove duplicate "All" publication menu link
- Apply chosen to related policy field
- D8CORE-6312 Fix node title not reflecting the changes (#629)
- D8CORE-5250 Add "Last Updated" to search results items (#619)
- Modify directory permissions when copying the file during install (#626)
- Added view to display policy child pages (#624)
- D8CORE-6288 Policy content type (#617)
- D8CORE-6247 Add "Code" to WYSIWYG Style dropdown
- D8CORE-6242 D8CORE-4977 D8CORE-6055 Improve people lists and add pronoun field (#615)
- Disable confirm-leave js on CI environments
- D8CORE-6244: fixing float with a  clear (#614)
- D8CORE-4363: Sjpw images in cards at all breakpoint (#610)
- D8CORE-6235 Fix "Save and Import" on importer forms. (#613)
- D8CORE-6058 Adjust, improve, and add metatags for content (#608)
- D8CORE-6217 Allow configuring maximum main menu depth (#611)
- D8CORE-5825 Add taxonomy field to media types for categorization (#609)
- D8CORE-6245: fix to the transparent localist event link (#612)
- D8CORE-6224 Added localist bookmark url for importer (#607)
- D8CORE-5955: Added journal publisher field, updated publisher label. (#602)
- D8CORE-5656 D8CORE-6215 D8CORE-6048 Adjustments to people node form and views (#606)

9.1.3
--------------------------------------------------------------------------------
_Release Date: 2022-08-22_

- Fixed courses field widget with view query parameter.

9.1.2
--------------------------------------------------------------------------------
_Release Date: 2022-08-15_

- D8CORE-6219 Adjust help text and remove aria label help text
- D8CORE-6168 Default to 12 column widths on paragraph row items

9.1.1
--------------------------------------------------------------------------------
_Release Date: 2022-08-10_

- Fixed some styles for stretched link cards and colors.

9.1.0
--------------------------------------------------------------------------------
_Release Date: 2022-08-09_

- CAWD9-136 Corrected hero behavior for composer autoload issue
- Updated field validation for google analytics and allow multiple property ids (#600)
- Modify json api endpionts and add nextjs consumer support (#599)


9.0.0
--------------------------------------------------------------------------------
_Release Date: 2022-08-03_

- CAWD9-136 Added centered option for banner paragraph
- CAWD9-134 Card background color options
- CAWD9-135 Slight image zoom on hover for cards and news items
- Add some margin to the page top of sweeteners, improve comparison tool a11y (#91)
- Wait 1 second for ckeditor click events
- D8CORE-6006 Disable link attributes module (#597)
- Improve person importer url fetching with some error handeling
- Add functional tests for wysiwyg button focus (#593)
- Validate menu items for local absoluate urls (#594)
- Update StanfordTextEditorTest unit test(#595)
- D8CORE-5867 Simplify and unify rabbit hole message and action (#592)
- D8CORE-5975 Allow resetting taxonomy term order to alphabetical with the button (#591)
- Updated field encrypt module to version 3.0.0
- Updated configs for latest core and contrib modules
- Hide "Sticky" and "Promote" fields on node form
- Fix duplicates in course lists
- D8CORE-6035 Show image title field when uploading in media library.
- Ensure events importer widget works when the API is empty
- Removed entity_print from composer.json (#589)
- Adjust and update tests
- D8CORE-5684: Underline buttons on the events mini calendar. (#586)
- Updated config from search_api module
- D8CORE-6000: Added additional html elements to embeddables allow list (#584)
- D8CORE-4183: fix up to alignment. (#569)
- D8CORE-6003 Save terms in the order they were chosen (#583)
- D8CORE-6005 Allow Span tags in the wysiwyg
- D8CORE-5128 Enable embed code validators (#579)
- Refactored and improved codeception tests.
- fixed composer namespace to lowercase
- Removed fzaninotto/faker workaround in CI tests
- D8CORE-5948: removing the li from the ch line limit (#578)
- D8CORE-5862: Removed obsolete checkbox from theme settings (#575)
- Fix acceptance tests (#86)
- Fixed cirlceci tasks
- D8CORE-4972 Provide aria-label input for links on paragraphs (#573)
- removed unwanted composer files
- Updated drupal core 9.4
- Improve event sponsor field update hook (#574)
- D8CORE-3558 Fix error when the access token expires (#566)
- Move some CircleCi to GH Actions (#568)
- D8CORE-5860 Fix intranet icons for paragraphs and media
- D8CORE-4780 Changed search page button text to "Search"
- D8CORE-2274: Updated event sponsor field "Add More" button label (#570)
- D8CORE-4489: fixing font sizes within tables (#564)
- D8CORE-5598 D8CORE-5592: making margins even on OL and UL (#565)
- D8CORE-5886 Enable ajax on people lists
- Added and configured ckeditor_blockimagepaste to prevent inline base64 images
- D8CORE-4858 Allow hiding paragraph and custom empty results message (#563)
- D8CORE-5864: fixing the news alignment. (#559)
- D8CORE-5859: changes to the font sizes in courses (#562)
- synced fontawesome settings with stanford profile
- Adjusted VBO form for event date fields that are required
- D8CORE-4867 Publication lists on people pages (#561)
- D8CORe-5871 Change order of filter processing to fix <picture><source> tags
- D8CORE-5858 Add missing "All" courses menu item
- Locked citeprocphp to version 2.4.1, pre ext-intl requirement. (#560)
- D8CORE-5763 Updated default content (#558)
- D8CORE-5680 Switch list landing pages to nodes with layout builder settings (#552)
- Updated font awesome settings
- D8CORE-5773: Added edit buttons on courses list page (#555)
- Updated some codeception tests (#554)
- Updated composer dependencies
- fixup for 9.x
- Changed circleci automation tasks to 9.x branch
- D8CORE-1835: Added abbr buttons to ckeditor (#550)
- Disabled courses department importer
- D8CORE-2215: Let editors sort content by author (#551)
- D8CORE-5824: Added a second provider for Stanford University Library oEmbeds (#548)
- Updated circleci docker
- Updated submodules (#547)
- gitpod configuration
- Consolidate dependency modules into a mono-repository (#523)
- Change stanford_profile dependency for continued maintenance


8.x-4.2
--------------------------------------------------------------------------------
_Release Date: 2022-05-11_

- Restored custom caw event view.

8.x-4.1
--------------------------------------------------------------------------------
_Release Date: 2022-05-11_

- Updates from stanford_profile (#78)
- fixed tests
- Enabled aggregation for duplicates in course view
- Added courses department importer for long name population
- D8CORE-2331: Updated help on media caption field text (#542)
- Allow admins to change the home page via site settings (#540)
- D8CORE-5833: Fix for courses view to respect chosen limit in card grid list paragraph (#539)
- Updated layout builder logic

8.x-4.0
--------------------------------------------------------------------------------
_Release Date: 2022-05-03_

- Updates from stanford_profile 2022-05-03
- Updated configs and styles
- updated dev dependencies (#536)
- Added twitter card metadata for person content
- Several tweaks to the taxonomy display and fields. (#532)
- Update block.block.minimally_branded_subtheme_pagetitle.yml (#535)
- D8CORE-5772: Added custom block and edit link on `/courses` page (#534)
- D8CORE-5748: Adding a listy style to the taxonomy terms (#533)
- D8CORE-5778: adding the grid col 3 for three across (#530)
- D8CORE-5627: added <object> and <param> to allowed tags in embeddables (#529)
- D8CORE-5729 People term pages: display only child terms groupings (#526)
- D8CORE-5187 Courses and Importer(#500)
- localist terms (#522)
- Updated circle ci steps
- Fixed book menu active trail
- D8CORE-5611 Allow multiple basic page types and change widget
- D8CORE-5696 Only display location address once (#520)
- D8CORE-5629 Adjust profile link url for stanford only profiles (#521)
- D8CORE-4118 Remove layout builder settings on other display modes (#519)
- D8CORE-4128 Change views to HTML lists (#518)
- Enabled transliterate_filenames from stanford_media
- D8CORE-3975 Created shared tags vocabulary and fields (#511)
- D8CORE-5666 Enabled and configured responsive_tables_filter module (#515)
- Dont trim the url on even cards
- Updated link_title_formatter module
- Updated domain_301_redirect version

8.x-3.2
--------------------------------------------------------------------------------
_Release Date: 2022-04-13_

- Fixed subsite menu expanded.

8.x-3.1
--------------------------------------------------------------------------------
_Release Date: 2022-03-24_

- Adjusted subsite caches
- D8CORE-5172: Updated references to localist and events-legacy urls
- DO not require lockup option select, prevent requiring lockup fields

8.x-3.0
--------------------------------------------------------------------------------
_Release Date: 2022-03-18_

- Fixed up config
- Updated circleci branches
- switch master branch to main
- Configure layout builder restrictions consistently (#509)
- Enable minimally branded theme for easier switching (#508)
- adjusted VBO dependency to inherit from stanford_actions
- Updated google analytics to latest 4.0 version
- D8CORE-3345 Updated path auto pattern for events, news, and people (#505)
- Added jsonapi_extras and disable write access
- D8CORE-4704 Fix person list to show nested content (#506)
- D8CORE-4526 Adjust full width layout for page title position (#497)
- D8CORE-5583 enabled views_custom_cache_tag module
- Process Localist html to fix <img> tag styles to attributes (#504)
- Removed scheduler from media and taxonomy terms
- conditional fields (#503)
- Updates from stanford_profile 2022-03-09
- Added permission for scheduler
- Added and enabled webp for performance improvement
- Modified the revision test to have a dynamic page title.
- Enabled pdb_react module.
- D8CORE-2893: Added minimally branded subtheme (#492)
- D8CORE-5180 D8CORE-5227 Remove alt text on people images (#498)
- D8CORE-4713 Added id attribute for several wysiwyg tags (#496)
- D8CORE-4974 Added a third content block for the local footer (#491)
- BOT-8: Adjusted file upload access test for Intranet and allow_file_uploads. (#493)
- Updated config and tests for smartdate module update (#494)
- D8CORE-5278 Added scheduler module and configured for all content types (#486)
- preg_replace of null is deprecated in php 8, use strings (#490)
- Fix pathauto parent path generation (#489)
- D8CORE-5236: Updated text on "Load More" buttons to be more descriptive (#483)


8.x-2.1
--------------------------------------------------------------------------------
_Release Date: 2022-02-08_

- Increase height of benefits comparison tool
- Updates from stanford_profile 2022-02-06
- Improved subsite cache mechanism for the lockup block
- Improved cache tags and context for the subsite branding block
- Allow event type to be changed
- Adjust events list view to have multiple display methods
- Adjust sweeteners and event series path auto pattern
- Updates from stanford_profile (#60)
- Fixed the visibility conditions for the search block (#482)
- D8CORE-5398 Rename Legacy events importer field (#481)
- Added administrative file list and delete actions (#480)
- Bugherd Minor Fixes
- Added permissions for CSV importer
- D8CORE-5221 D8CORE-4857 D8CORE-3517 Small adjustments to configs and content. (#476)
- Add more steps to the site search disable tests (#478)
- D8CORE-5193 Configure localist importer to forget about old events (#475)
- Accessibility Improvements
- Accessibility fixes for the comparison tool
- Added career celebrations importer
- Move news and events lists under /engage (#50)
- search block visibility
- Career Celebrations CSV Importer
- Added localist url to external source field (#471)
- Adjust localist end dates for Smart Date "All Day" (#474)
- CAW21-114 CAW21-115 Add permissions for new taxonomy
- CAW21-114 CAW21-115 Event Series and Basic page fields and view filters.
- Updated configs and added layout_paragraphs patch
- Added checkbox to hide site search in site settings config page (#472)
- D8CORE-5183: changes to external links config to skip localist (#473)
- D8CORE-5119: added help text for the localist import field. (#470)
- updates from stanford_profile (#47)
- clean config export
- Upgrade drupal core to 9.3.0 with config updates (#469)
- Hyvor Talk Commenting block (#46)
- Caw21-113: updates to the career celebrations cards (#45)
- Adjust book outline routes for better access and labels
- Disallow other content types from subsite
- fixed permissions and added job description library redirect
- Updates from stanford_profile (#44)
- Adjust events test to pass
- D8CORE-4521 Localist Events Importer (#463)
- Change profile helper module weight for priority
- D8CORE-4677 Use an access token to fetch stanford-only profile images from CAP (#467)
- Bugherd 24 Adjust chosen element widths
- updated benefits view
- Bugherd 28 Uncheck all options if the user hits the back button
- Bugherd 29 & 25 Benefits comparision tweaks
- D8CORE-4860 Wrap publication importer body text in <p> (#466)
- D8CORE-4996 Remove taxonomy term on basic page card and list displays (#465)
- Merge pull request #36 from SU-SWS/CAW21-106
- CAW21-106: adjustments to the h2, and search for the sweeteners.
- CAW21-106 Created side by side sweetener list with filter on the left
- D8CORE-4246 Add fontawesome module for wysiwyg icon support (#464)
- D8CORE-4876 Enable ajax on publication list view
- D8CORE-4824 Disable accordion on event series
- D8CORE-4878: Added configs for oEmbed Providers module (#462)
- D8CORE-4871: adding a class to the table element and the aside element (#460)
- Bugherd 18: Move manager toolkit to a subsite
- moved theme styles to the helper module for use on the admin pages
- Bugherd 22: Fix card display for events and news
- fixup the search indexing display mode
- Bugherd-14: Display news images in list paragraphs
- CAW21-101 One time edit link for career celebrations (#28)
- CAW21-111 Added text to top of compare table
- Set default image for career celebrations
- Fix up styling and improve careers view (#35)
- Add table headers in the comparison table (#34)
- Refactored and moved templates and benefits javascript for improved performance
- CAW21-108 Remove permissions for book module (#33)
- CAW21-86: Setting up the benefits node page.
- Fixed list paragraph not saving
- CAW21-110: updating the disabled styles (#31)
- Caw21-88: Styling updates to the comparison pages (#29)
- Updates from stanford_profile (#30)
- disable intranet tests
- D8CORE-4816 Add configurable allowed tags for unstructured embed (#461)
- Added publications help text
- Index intranet content (#458)
- adjusted publication importer label
- CAW21-87 Build benefits comparison tool (#26)
- adjusted chosen settings
- CAW21-107: moving the display of the cards (#27)
- Add citation to clonable entities
- tweaks to publication csv importer for easier data
- Added site manager permission to import publications
- CAW21-80 Career Celebrations styles
- BUGHERD-6 Enable limited html for card paragraph
- Adjusted the benefits importer process plugin
- Stanford Profile Updates 2021 09 28 (#25)
- Enabled localist embed validation
- Exported config after database updates with latest contrib
- D8CORE-3749 Publication CSV importer
- D8CORE-4693 Filter events by the second instead of the day (#457)
- D8CORE-4759 add specific view mode for search indexing for better control (#454)
- D8CORE-4096 Updated help section text (#455)
- Fixed uneditable tables in they wysiwygs
- corrected layout styles to match the old react paragraphs styles
- CAW21-85 Benefits content type
- D8CORE-3026 added and configured stanford_actions (#453)
- Added layout builder role (#451)
- Updates from stanford_profile 2021-09-15
- Configure the new media embeddable validator (#450)
- D8CORE-4534: adding a skip anchor block to the filtered pub page (#437)
- D8CORE-4534: adding a skip anchor block to the filtered pub page (#437)
- D8CORE-4749 Add and configure views bulk edit (#449)
- Update from stanford_profile (#22)
- Updated layout paragraph settings
- removed patches no longer needed
- Padding adjustment for paragraphs in layouts
- Update layout paragraphs patch
- Changed layout paragraphs patch
- Updates from stanford_profile (#19)
- Create FAQ paragraph with accordion lists (#14)
- CAW21-47 Add FontAwesome module for icons support in cards (#13)
- added another patch for layout paragraphs
- D8CORE-4643 Swapped out the "menu block" block with a regular "menu" block (#446)
- CAW21-38 Implement layout paragraphs to replace react paragraphs on basic pages. (#10)
- Adjusted media filtering to allow filter on image alt text
- Added media category for easily filtering images (#11)
- Added and configured permissions for limited HTML format
- CAW21-77 Styling for sweeteners node page
- D8CORE-4653 Add editoria11y module with permissions for contributors (#445)
- CAW21-78 Style Sweeteners list and filter view (#9)
- added cache rebuild
- D8CORE-4681 Enabled rabbit hole taxonomy support (#443)
- D8CORE-4350 Sort publications by month and day along with year (#444)
- CAW21-82 Build accordion paragraph component (#440)
- D8CORE-2277: updated fields help text (#441)
- CAW21-79 Added career celebrations content type (#8)
- D8CORE-2278: Tweaks to Event Series help text (#442)
- CAW21-76 Build sweetener content type and views (#7)
- D8CORE-4668 Allow new google analytics account id format `G-` (#439)
- Updates from stanford_profile 2021-07-21
- Adjusted mathjax module CDN url
- D8CORE-2594 Allow menu placement under unpublished pages (#435)
- D8CORE-4497 Replaced <h3> with <p> in views no results text
- D8CORE-4235 Require alt text on gallery images (#433)
- D8CORE-4504 Added mathjax for latex formula support (#434)
- D8CORE-4194 Add url text for the link on media w caption paragraph (#423)
- Added and enabled the PDB module (#432)
- D8CORE-4378: adding the skip to main content on these list page layouts that were missing (#431)
- CAW21-35 Enable and configure book module for subsites (#3)
- Corrected the events schedule display settings for the pattern refactor
- Exported configs after Drupal 9.2.0 upgrade (#429)
- Corrected acceptance tests with the latest dependency updates (#427)
- D8CORE-4090 D8CORE-2888 D8CORE-4126: accessbility changes to the news modules. (#425)

8.x-2.0
--------------------------------------------------------------------------------
_Release Date: 2021-06-08_

- Initial release cut from stanford_profile
