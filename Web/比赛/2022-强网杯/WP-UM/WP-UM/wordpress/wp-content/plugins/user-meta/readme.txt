=== User Meta - User Profile Builder and User management plugin ===
Contributors: khaledsaikat, sourov
Tags: user-management-plugin, member, user-export, user-profile, user-registration, email-notification, user-login, front-end, redirection, user-listing, captcha
Requires at least: 4.7
Requires PHP: 5.6.0
Tested up to: 5.9
Stable tag: 2.4.3
Copyright: User Meta, https://user-meta.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html 

An easy-to-use user management plugin that allows user login, reset-password, profile update and user registration with extra fields, all on front-end and many more. 

 

== Description == 

An easy-to-use user profile and management plugin for WordPress that allows user login, reset-password, profile update and user registration with extra fields, all on front-end and many more. User Meta Pro is a versatile user profile builder and user management plugin for WordPress that has the most features on the market. User Meta aims to be your only go to plugin for user management. We believe in synergy so we've solved all of your problems in one single plugin.

With just a few clicks you’ll be able to create your own personalized user registration, and login forms that you can add anywhere on your site. You’ll be able to grow and manage an extensive online community with speed and ease.  If you have an online store or a membership site, then this plugin is a must have!

<iframe width="560" height="315" src="https://www.youtube.com/embed/rqf7uQ9dTcA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

== What User Meta is offering you == 

**Front End Support** Create beautiful and highly customizable login/registration page, separate page for each user, and many more interfaces for the users sitting in front of their screen.

**In House Form Builder** Build your own forms for anything you want drag and drop interface (unlimited, no strings attached). Check out our supported form fields below.

**Newbie Friendly- No Prior Knowledge or Experience Required** You don’t have to be a tech wiz or hire an expert to create and manage your membership website. Our no-code, drag and drop plugin allows you to start creating and customizing user login and profile pages within minutes.

**Increase Your Conversion Rates** User Meta Pro allows you to change your default WordPress Login Page into a customized login page with a user management builder that’s designed to boost conversion.

**Improve Your WordPress Website Security** User Meta Pro allows users to register on your WordPress site without accessing the admin area. It enables fast and seamless user login without compromising the security of your website. 

**Grow and Manage Your Online Community Fast** Build customized login and user profile pages and create a better user experience. User Meta Pro makes it easier for you to manage your site members and users with ease. 

**Email Support** Get hands-on control over your email notifications on each user action, you can set what your users see as sender email, or sender name instead of the default wordpress@example.com. Oh, did we mention the user verification via email?

**Fight The Bots** You'll be getting free captcha support in your forms, so no bot registration is guaranteed 

**Widgets** Multiple widgets like personal profile, registration prompts 

**Custom Designed Login Page** Get your users a well-designed login page of your choice instead of bulky WordPress's default one 

**Organize Everything by Roles** Get different pages, login forms based on user roles. You decide who sees what. 

**Optional Ajax Support** Set your form's submission as you wish, full page reloads or not, completely up to you 

**Export/Import User** You'll have the options to save your user data as exported csv files  and can import user on a (premium). You can choose to encrypt data if you wish, we respect your strong privacy policy.

**User Approval Option After Registration** You can check the registered users by hand to double check any spammers and then approve registration (premium)

**User Listing** List your users and show on frontend with public profile based on different criteria (premium)

**WooCommerce Integration** You can integrate easily with WooCommerce and expand with our add-on (premium) 

**Control Posts Based on User Roles Restrict** content based on WordPress User Roles (premium). You can set the tone for which role can see and do what via add on 

 

== Features  == 

* Front-end user login 
* User registration widget 
* User profile template 
* Create a custom form in WordPress 
* user profile service login 
* fix a corrupted user profile registration 
* Front-end user profile 
* Front-end reset password 
* Custom user login by username or email 
* User Profile widget, registration widget, and login widget 
* Add extra fields to customized user profile 
* Add extra fields to custom user registration 
* Add profile link to users listing page 
* Fields and forms editor to customize front-end user profile and registration 
* Create unlimited forms for custom user registration or profile 
* Show role-based user profile. Users from different roles can see different profile form 
* Users can register their account with the different roles by using the different registration form 
* Show user avatar to profile and registration page (both ajax and non ajax). 
* Change default email sender information (Let your user get an email from your preferred name and email instead of wordpress@example.com). 
* Use conditional logic to show/hide fields based on other fields. 
* Fight against spam by Captcha 

 

Read about all the plugin’s features at [User Meta Pro](https://user-meta.com/). 

== Extra Features for User Meta Pro (Premium Version) == 

* Auto user login after registration 
* Email verification on registration 
* Admin approval on user registration 
* Admin can activate or deactivate any user-by-user listing page 
* Add extra fields to default profile or hide existing fields 
* Role-based redirection after user login, logout, and registration 
* Customize emails sent from your WordPress site. Add default field or extra fields content to each email 
* Bulk users import/export from/to CSV file with extra fields 
* Customize, filter, or change user order while exporting users 
* User import/export with a hashed or plain text password
* Customize all email notifications
* User listing with different criteria
* Register new blog on multisite 
* User profile service login 
* 8+ Free add-ons

Get [User Meta Pro](https://user-meta.com/) or [Try Demo](https://demo.user-meta.com/) 

= Documentation =

**2 steps to get started**

1. Go to User Meta >> Forms. Create a form, give a name to your form and populate it with fields.
2. Write shortcode to your page or post. e.g.: Shortcode: [user-meta-profile form='your_form_name']

[View Documentation](https://user-meta.com/documentation/ "User Meta Pro Documentation")

Get [User Meta Pro](https://user-meta.com/ "User Meta Pro").

**Note**
The plugin stores all user's data to wp_usermeta table as WordPress standard.
So it is possible to other plugin talks with User Meta using WordPress standard.


== Installation ==

1. Upload and extract `user-meta.zip` to the `/wp-content/plugins/` directory or add the plugin from Plugin >> Add New menu
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Why error message, "User registration is currently not allowed." is showing in registration page? =
WordPress doesn't allow to register new user by default settings.
To allow user to register, go to Settings >> General page in admin section.
Checked the checkbox which is saying "Anyone can register" and Save Changes.

= Saving fields, forms or email notification settings in admin section displays 0 =
For very long form or lots of fields, you might encounter displaying 0 instead of saving,
If you ever have this issue, mostly it could be max_input_vars are exceeding.
To solve this problem increase max_input_vars in your .htaccess file.

`php_value max_input_vars 3000`

If you have suhosin installed, use following too.

`php_value suhosin.post.max_vars 3000`


== Screenshots ==

01. Simple registration form.
02. Simple profile form.
03. Front-end user registration with extra fields.
04. Front-end user profile with extra fields.
05. Supported fields for creating profile or registration form.
06. Front-end user login with reset-password tools.

== Changelog ==

= 2.4.3 =
* Fix: Security fix to prevent XSS attack

= 2.4.2 =
* Fix: File upload path traversal

= 2.4.1 =
* Fix: Proper escaping of fields and forms name

= 2.4 =
* New fields on pro version: Consent, Social Link, Video Link
* Changed field groups for future fields insertion
* Autofocus on first input
* Pattern-rules on Reset Password field
* Notice for feedback added
* Fix: User listing search function issue
* Pro: New Author Box addon

 = 2.3 =
* New fields on lite version: HTML, Captcha, Image URL, Phone Number
* Fix: Multiselect field's error on multi-page
* Fix: Bold label issue on settings page
* Pro: New Restrict Content addon

= 2.2.1 =
* Fix: Broken URL on "Get started" panel

= 2.2 =
* Login widget and form widget on the lite version.
* New fields on lite version: Multi-select, Date-Time, File Upload, Number, Url, Country, Custom Field, Page Heading, Section Heading
* Pro: New WooCommere addon

= 2.1 =
* Removed Jabber, Aim, and Yim fields
* Added French Canadian language support
* Drop HTTP_REFERER dependency from the form builder
* Fix: Unique Display Name validation
* Fix: End date inclusion in export
* Fix: Missing JS on user-meta-field shortcode
* Pro: Added User Listing addon

= 2.0.2 =
* Fix: error message on disable ajax login
* Updated translation files

= 2.0.1 =
* Fix: Multi-value fields sanitize

= 2.0 =
* Added reCAPTCHA v3 for captcha validation
* Apply sanitize_* and esc_* to missing fields
* Use latest composer bin to generate vendor (plateform and require 5.6.0)
* Bump requirements WP-4.7 PHP-5.6
* Remove unused files from the lite version (e.g reCaptcha, activationForm)
* Update js/css libraries to latest version
* Update html lib to version 1.1.0
* Fix: Partial export error removed caused by special characters

= 1.4.3 =
* Updated countries list
* Added user row options for multisite
* Fix: Pagination error removed

= 1.4.2 =
* Update compatibility for WP-4.4.0
* More specific error messages in form saving
* Fix: Regex added in phone field to fix bug
* Fix: duplicate meta value on user import (new users)

= 1.4.1 =
* ReCaptcha, timepicker, validationEnginn version update.
* Using data uri to export users.
* Fix: file upload size bug.
* Fix: exclude roles on user export.
* Added min and max required options for the checkbox.

= 1.4 =
* Added support for GDPR data export
* rich_text field accepts options by hook
* Fix: read-only for rich_text field
* Removed Reset password style
* Added user_meta_admin capability
* Minimum requirement WP-4.4
* Updated bootstrap to v3.3.7
* New filter hook: user_meta_wp_hooks
* Deprecated hook: user_meta_wp_hook
* Lite: Login by username or email or both of them
* Lite: Front-end lost-password and reset-password options
* Lite: Show logged-in user customized data, based on user role
* Pro: New built-in menu for add-ons management
* Deprecate using user-meta-advanced and user-meta-wpml addons separately
* New pro addon: BuddyPress xProfile Export
* Fix: pagination bug for non-pagination page
* Enhance security of file uploader

= 1.3 =
* Allow array in roles
* Pro: Export and import multiple roles
* Pro: Separate options for different captcha
* Pro: Scroll to top with page heading
* Code: New filter hook: user_meta_recaptcha_request_method
* Code: Removed PHP7.1 depreciated mcrypt_encrypt dependencies
* Code: Update ReCaptcha to 1.1.3
* Code: Minimum requirements for the plugin is PHP-5.5
* Fix: Page navigation bug
* Fix: Role export bug

= 1.2.1 =
* PHP7 compatibility
* Modify class names according to PSR-2 standard
* Added more options to captcha
* Lite: Add login features to lite version
* Fix: print inline js to backend profile (date-time was not working)
* Fix: bug on user importing extra fields
* Fix: Login error message bug
* Fix: Conditional logic for checkbox, radio, and multi-select
* Fix: Using multiple captchas on the same page

= 1.2 =
* Visual options selector for dropdown, multi-select, radio and checkbox
* Code: Minimum requirements for the plugin is PHP-5.4 and WP-3.7
* Code: Using namespace and rewriting field generation classes
* Code: Apply PSR-2 coding standard to all PHP files
* Code: Rewrite all field generation codes
* Optimized for wpml support
* Update translation files
* Added user_meta_loaded action hook to load extensions
* Put inline front-end JavaScript to footer
* Added tooltip to field editor
* Remove placeholder as field label type, as placeholder has its own field.
* Force uploaded file name to be lowercase and special character escaped.
* New Hooks: user_meta_field_config_render, user_meta_field_config_before_update, user_meta_form_config_render
* Style file remove link
* Delete avatar and files while deleting a user
* Delete old files & avatar on profile update
* Pro: Redirection to a page
* Pro: Export users by form_id and write to file
* Pro: Renamed user-meta directory to user-meta-pro for pro version
* Fix: re-validation for regex input
* Fix: Storing admin approval emails bug
* Fix: Email notification selection tab collapse
* Fix: Bulk users export issue
* Fix: Password reset issue since WP-4.3
* Fix: Add user to blog, without user_login but user_email
* Fix: Don’t let existing user register again for the same site under network
* Fix: WP-4.5 compatibility

= 1.1.7.1 =
* Fix: Duplicate field id in case of form import
* Check if other reCaptcha library is exists, to avoid conflict.
* Fix: Add user to blog
* Fix: Rich text url bug

= 1.1.7 =
* Redesigned fields and forms editor.
* Conditional logic.
* Username will remain same as email while registration without username.
* Remove base64_decode.
* Switch uploader code to admin-ajax.php.
* Remove html5 required validation, add html5 regex to custom field.
* Strip @noreply.com.
* Added Turkish and Czech translation.
* Added regex to password field.
* Pro: Allow to hide extra social fields from backend profile.
* Pro: reCaptcha v2.
* Pro: Added %generated_password% placeholder.
* Pro: Remove password & email field from standard fields set. Those fields can can be used via “custom fields”.
* Pro: Add retype_label to custom field.
* Pro: Added yearRange to datetime field.
* Pro: Profile update email for backend, track modified email.
* Pro: Added filter: user_meta_countries_list filter to countries list.
* Pro: Separate email verification and admin approval processes.
* Fix: Pagination bug.
* Fix: required checkbox error.

= 1.1.6 =
* Support user registration for free version.
* Remove plugin-framework.css/js. Split user-meta.js into user-meta.js and user-meta-admin.js
* Optimize the plugin for user-meta-advanced add-on.
* Allow action/filter hook enable/disable by ‘user_meta_wp_hook’ filter.
* Added Chinese translation.
* Fix: postbox toggle icon and allows drag texts inside postbox.
* Removed users_can_register option dependencies for user registration form.
* Added role placeholder.
* Added ver paramater to js and css files.
* Pro: Shortcode: [user-meta-field id=Field_ID] for showing single field.
* Pro: Shortcode: [user-meta-field-value id=Field_ID] to show stored field content.
* Pro: Store resetpass hashed key instead of plaintext as of WP-3.7
* Pro: Added Lost Password email notification.
* Pro: Number field allows integer and decimal point.
* Pro: Filter: user_meta_admin_email_recipient to filter admin notification email.
* Pro: Added multiselect field and allow optgroup on select.
* Pro: Import both plain text and hashed password with users import.
* Pro: Allow override of WordPress default user registration and reset password email by add-on.
* Pro: try to send single email when sending multiple email at a time failed.
* Pro: Added registration link with login form/widget.

= 1.1.5 =
* Add user_id parameter to user_meta_pre_user_update filter hook.
* Remove not used uploaded files via schedule events.
* Add Russian translation. (Thanks to Vitaliy Cherednichenko for his translation)
* Add placeholder support to Fields Editor.
* Change file upload directory to /uploads/files/
* Change logout url. Logout url is not using resetpass anymore.
* Add html5 placeholder to field by user-meta hook.
* Bug fix: Required validation for country field.
* Add settings for customize UMP generated text for front-end.
* Pro: Allow to send email notification for all users who have administrative role.
* Pro: User password will not force to include in registration email notification.
* Pro: Add option for use default lostpassword url.
* Pro: Remember last user import settings.
* Pro: Use separate page for reset password and email verification.
* Pro: Login redirect will not show any message while redirecting.

= 1.1.4.1 =
* Add Captcha for login form.
* Fix import UMP and pagination bugs.

= 1.1.4 =
* Use TinyMCE as rich text editor.
* meta_key auto generate from field title.
* Enable translation for dynamic text.
* Disable free to pro one click update as WordPress plugin guidline.
* add allow_custom in datetime field to allow more customization by js.
* Added alternate method when allow_url_fopen=0 for showing uploaded image.
* Field validation in both server and client side.
* Pro: Introduce new field type "Custom Field" to add custom regex and error message.

= 1.1.3 =
* Assign form to login widget.
* Allow role based profile as widget.
* Replace type=both into type=profile-registration
* Replace type=none into type=public
* type=public allow user_id as $_GET for showing public user profile.
* Add type=login to form widget.
* Change date format and filter hook.
* Default role selection.
* Shortcode generator popup.
* Clickable checkbox and radio.
* Added filter: user_meta_pre_configuration_update for fields_editor, forms_editor and settings.
* Action: user_meta_load_admin_pages
* Filter: user_meta_execution_page_config
* Filter: user_meta_default_login_form
* Aded filter support to lost password form and deafult login form.
* Theme for reCaptcha.
* Check user access by "add_users" capability.
* Clickable users listing for Active | Inactive | Pending | Pending Approval
* Change email verification and reset password process.
* WordPress-3.5 compatibility.
* UMP Export-Import fields, forms, settings.
* Role based profile showing.
* Allow role selection on registration/profile (admin can choose which roles user can select).
* Field title position: Top, Left, Right, Inline, Hidden.
* Added “Auto login after user registration" feature.
* Fixes: Password changing from frontend.
* Image crop for avatar or file upload.
* Single pot file.
* Enable SSL admin.
* Assign custom form with login widget/login form that allow to use custom field, class name, changing button text/class.
* Integrate plugin-framework.pot with user-meta.pot (single pot file instead of two).
* Provide more action/filter hook in every steps.
* Allow to use placeholder under html field.
* MU: New blog registration.
* MU: Add user to blog.
* MU: added option for prevent login for non-member for current blog.
* Registration/Profile widget.
* Registration/Profile Template Tag.
* Extended users export.
* Allow to change button's text and css class of form.
* Custom email notification for profile update(both user and admin).

= 1.1.2 =
* One click upgrade to Pro version.
* Add default email sender support.
* Pro: One click version update.
* Pro: Login widget. Showing role based user data with login widget or shortcode.
* Pro: Extra fields in backend profile.
* Pro: Role based customizable email notification with extra fields.
* Pro: Import users from csv file including user's meta data.
* Pro: Front-end lost password and reset password tools.
* Pro: User email verification on registration.
* Pro: User activation and deactivation.
* pro: Role based user redirection on registration, login and logout.
* Fix: Arbitrary File Upload Vulnerability

= 1.1.1 =
* Added Support while fail AJAX call

= 1.1.0 =
* Include first version of User Meta Pro
* Pro: added more fields type
* Pro: Frontend Registration
* Pro: Frontend Login with username or email

= 1.0.5 =
* Changing complete structure
* Make Seperation of fields and form, so one field can be use in many form
* Add verious type of fields
* Added dragable fields to form
* Improve frontend profile

= 1.0.3 =
* Extend Import Functionality
* Draggable Meta Field
* Add Donation Button

= 1.0.2 =
* Optimize code using php class.
* add [user-meta-profile] shortcode support.

= 1.0.1 =
* Some Bug Free.

= 1.0.0 =
* First version.