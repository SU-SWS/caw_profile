# Cardinal at Work Profile

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
