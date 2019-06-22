# Promotional Packages System (for Osclass)
Sells promotional packages for its users, can be associated with an official Osclass payment plugin, so that you pay monthly, quarterly or annually for the same package after its expiration date or buy a higher one (upgrade) if the user wishes to have more Free publications available.

### Features
- NOTE: Automatically once the plugin is installed, it does not allow users to reach the publication form, they must do so with an assigned package and with available publications.

- Set a default package (optional), when the user it join at your site, automatically it is assigned the default package for her first free listings.

- NOTE: When a user update her profile and change the type of account to a 'Company', it will lost package that has and vice versa.

- Packages of one type are only assigned to the same type of user account.

- When a user account is deleted, all traces of activity are also deleted with the Promotional Packages plugin.

- When the assignment of a packet expires, it sends a message in the 'Dashboard' section every time it enters there, warning the expiration.

- When the allocation of a package expires, it does not allow the use of the assigned package and therefore it does not publish either.

- When the allocation of a package expires, it does not allow maintaining an active publication. NOTE: Inactive publications can not be edited.

## Hooks

	packages_into_form_settings

	packages_admin_menu_init

	extension_user_bulk

	custom_admin_packages_settings

	custom_admin_packages_settings_done

	before_packages_profile_info

	after_packages_profile_info

To allocate packages massively from 'Mange users', uncomment the line 79 of index.php file of plugin.
And the following code paste it in oc-admin/themes/modern/users/index.php since line 279:

	<?php osc_run_hook('extension_user_bulk'); ?>

# Filters

	packages_modules_filter

	packages_info_current_filter