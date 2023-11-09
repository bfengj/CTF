Yii Framework 2 bootstrap4 extension Change Log
==============================================

2.0.10 May 05, 2021
-------------------

- Bug #173: Fix bug with error messages not being displayed for checkbox, radio, checkboxList, and radioList (egrekov)
- Enh #205: Added `Toast` widget (simialbi)


2.0.9 November 10, 2020
-----------------------

- Enh #191: Added `centerVertical`, `scrollable`, `dialogOptions` options to Modal and `role="document"` to `modal-dialog` element (adhayward)
- Enh #196: Added ability for `Accordion` to expand a certain card (hoaaah, Mister-42, simialbi)
- Enh #197: Added support to override every class (simialbi)


2.0.8 October 08, 2019
----------------------

- Bug #177: Fixed non-functional headerOptions in Tabs (Mister-42)


2.0.7 August 13, 2019
---------------------

- Bug #163: Fixed error messages for checkbox and radio lists (simialbi)
- Enh #165: Allow override all classes via widget factory (simialbi)


2.0.6 July 23, 2019
-------------------

- Enh #93: Docs: Migration from yii2-bootstrap (simialbi)
- Enh #95: Brought back `$barOptions` (simialbi)


2.0.5 July 02, 2019
-------------------

- Enh #150: Set custom checks and radios as default behaviour (simialbi)


2.0.4 June 11, 2019
-------------------

- Bug #128: Fixed inputTemplate invalid-feedback (simialbi)
- Bug #133: Menu items were never activated (Mister-42, simialbi)


2.0.3 June 04, 2019
-------------------

- Bug #131: Fixed element with role tab must be inside role tablist (simialbi)
- Bug #135: Parent `li` should not contain active class (Mister-42)
- Bug #138: Restored functional itemOptions in Dropdown (Mister-42)
- Bug #140: `aria-expanded` should not be set with boolean in yii\bootstrap4\Accordion (Mister-42)
- Enh #129: Added `Modal::SIZE_EXTRA_LARGE` constant (shoomlix)
- Enh #137: Added disabled option for Nav, Dropdown and Tabs (Mister-42)


2.0.2 April 30, 2019
--------------------

- Bug #121: Fixed Progress widget with Russian locale (simialbi)
- Enh #120: Added Pagination (simialbi)


2.0.1 March 17, 2019
--------------------

- Bug #108: Tabs::$encodeLabels was not considered when encoding labels (machour)
- Bug #108: Fixed rendering with custom id: prevent double id rendering (simialbi)
- Bug #115: Wrong class in Toggle Button Group (simialbi)
- Bug #117: Fixed missing validation error message at fileInput field (simialbi)
- Bug #137: Remove role="navigation" from yii\bootstrap4\NavBar according to aria specification (Mister-42)


2.0.0 January 28, 2019
----------------------

- Initial release (forked from yii2-bootstrap and adjusted to fit bootstrap 4)
