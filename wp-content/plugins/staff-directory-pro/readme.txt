 === Company Directory ===
Contributors: richardgabriel, ghuger
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V7HR8DP4EJSYN
Tags: staff, directory, directory plugin, staff directory, staff skills, skills matrix, directory with contact form, staff skills matrix, staff skills directory
Requires at least: 3.5
Tested up to: 4.3.1
Stable tag: 1.7.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Company Directory is a simple-to-use plugin for adding Staff or Faculty Members with a Directory to your WordPress Theme.

== Description ==

The Company Directory is an easy way to add your Staff to your website.  Staff and Faculty are presented in several easy to understand layouts, including a list and single views, allowing visitors to get to know your company and capabilities. 

Company Directory allows you to include many pieces of information on each Staff or Faculty member, such as:

* Full Name
* First Name
* Last Name
* Job Title
* Phone Number
* Email Address
* Mailing Address
* Web Address
* Photo
* and Bio!

= Display Your Staff and Faculty in an Easy to View Format =

Company Directory includes a useful List view for displaying your Staff Member.  Visitors will be able to click through from your List views to read more about each Staff Member in a customizable Single View.

Company Directory Pro includes additional attractive layouts such as the Grid or customizable Table layout, which gives user the option to limit what fields are displayed, for ease of navigation.

= Templating System allows Complete Customization =

Company Directory uses a customizable template system to allow full control over the look and feel of Staff Members.  Have a large staff or faculty?  Company Directory includes Search functionality to facilitate users finding the person they are looking for, without having to navigate long tables and lists.  For Advanced Search functionality, check out Company Directory Pro!

= Easy to Use Features Simplify Administration =

Managing and displaying a large amount of Faculty or Staff on your website can be difficult - especially when using traditional methods such as editing HTML on a page.  With Company Directory, users are given an easy-to-understand method of adding and managing Staff and Faculty on their website.

Using our simple shortcode system, website maintainers can insert a list, table, or grid of Staff or Faculty onto any WordPress Page!  This allows visitors to view your Staff or Faculty, find who they are looking for, and contact them directly!

Our professional development team is continually improving and updating our plugin, so stay tuned for updates!

= Premium Support Available =

The GoldPlugins team does not provide direct support for the Company Directory plugin through the WordPress.org forums. However, direct email support is available to people who have purchased Company Directory Pro. 

The Pro Version of Company Directory includes advanced features such as Bulk Import and Export with a CSV Import/Export feature - saving countless hours of data entry! The Pro Version of Company Directory also includes a Table View, and a Grid View, Advanced Search functionality -- all in addition to direct support! 

[Upgrade To Pro Now!](http://goldplugins.com/our-plugins/company-directory-pro/upgrade-to-company-directory-pro/ "Upgrade to Company Directory Pro")

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the contents of `/staff-directory/` to the `/wp-content/plugins/` directory
2. Activate Staff Directory through the 'Plugins' menu in WordPress
3. Visit this address for information on how to configure the plugin: http://goldplugins.com/documentation/company-directory-documentation/

### Adding a New Staff Member ###

Adding a New Staff Member is easy!  There are 3 ways to start adding a new Staff Member

**How to Add a New Staff Member**

1. Click on "+ New" -> Staff Member, from the Admin Bar _or_
2. Click on "Add New Staff Member" from the Menu Bar in the WordPress Admin _or_
3. Click on "Add New Staff Member" from the top of the list of Staff Members, if you're viewing them all.

**New Staff Member Content**

You have a few things to pay attention to:

* **Staff Member Title:** This will be displayed, in the default list, as the Staff Member's Name.
* **Staff Member Body:** This is the Bio or Description of the Staff Member.
* **First Name:** This field is used for List, Grid, and other views where having the First Name separate is necessary.
* **Last Name:** This field is used for List, Grid, and other views where having the Last Name separate is necessary.
* **Title:** This is the Staff Member's Job Title, and it is displayed below their name in the default list.
* **Phone:** This field is displayed as part of the Contact Information meta data, below the Staff Member's Name, Title, and Bio.
* **Email:** This field is displayed as part of the Contact Information meta data, below the Staff Member's Name, Title, and Bio  This will be displayed as a clickable link.
* **Featured Image:** This image is shown to the left of the Staff Member.  We recommend using appropriately sized images for your layout.

### Editing a Staff Member ###

 **This is as easy as adding a New Staff Member!**

1. Click on "Staff Members" in the Admin Menu.
2. Hover over the Staff Member you want to Edit and click "Edit".
3. Change the fields to the desired content and click "Update".

### Deleting a Staff Member ###

 **This is as easy as adding a New Staff Member!**

1. Click on "Staff Members" in the Admin Menu.
2. Hover over the Staff Member you want to Delete and click "Delete".
  
  **You can also change the Status of a Staff Member, if you want to keep it on file.**

### Displaying a List of Staff ###

To display a list of staff on your website, use the shortcode ```[staff_list]``` in the page content area that you want them to appear.  

To limit the Staff displayed to a specific category, use the shortcode ```[staff_list category='the_slug']```, where the value of ```category``` is the slug of the Category you want displayed.  You can locate the slugs by looking at the List of Staff Member Categories.   

To show all of your staff members, grouped by category, use the shortcode ```[staff_list group_by_category='true']```.  You can control the order of the categories by using the attributes category_order and category_orderby, but the default is to sort them by their last name, in ascending order.

To paginate your staff list, use the staff_list shortcode with the per_page parameter set. For example:  ```[staff_list per_page='5']```.  Pagination links will be automatically added if needed.

Tip: You can also use a plugin such as 'Category Order and Taxonomy Terms Order' to order your Staff Categories.


### Displaying a Single Staff Member ###

To display a single Staff Member on your Website, use the shortcode ```[staff_member id="123"]```, where the value of id is the Staff Member's internal ID (you can get this shortcode by looking at the Staff Member List or the Edit Staff Member screen, inside WordPress.)

### Create your own Templates ###

Create your own templates for the staff list shortcode, the content of single staff member pages, and the staff search results page

To do so, navigate to the wp-content/plugins/staff-directory-pro/templates/ folder (note: this will vary a bit depending on your installation). In that folder you'll find a template file for staff-list.php and single-staff-member-content.php, which correspond to the [staff_list] shortcode and the single staff member pages, respectively. 

To create a search staff results page, navigate to your theme's folder copy index.php or search.php to search-staff-members.php, and make a visible change. Confirm that you see the visible change when searching for staff members.  If you are, you've setup the template right!

#### PRO: Table and Grid Views #### 
**Please Note:** Company Directory Pro is required to gain access to advanced features such as the Grid and Table views.

To display a Table of all your Staff Members, use the shortcode ```[staff_list style='table']```.  

To display a Grid of All Staff, use the shortcode ```[staff_list style='grid']```.  

Both of these styles work seamlessly with the new Group By Category feature.


== Frequently Asked Questions ==


= Can I use my own custom templates? =

Yes! You can use your own templates for the staff list shortcode, the content of single staff member pages, and the staff search results page.

To do so, navigate to the wp-content/plugins/staff-directory-pro/templates/ folder (note: this will vary a bit depending on your installation). In that folder you'll find a template file for staff-list.php and single-staff-member-content.php, which correspond to the [staff_list] shortcode and the single staff member pages, respectively. 

To create a search staff results page, navigate to your theme's folder copy index.php or search.php to search-staff-members.php, and make a visible change. Confirm that you see the visible change when searching for staff members.  If you are, you've setup the template right!

Make a copy of the template you'd like to modify in your theme's directory, and change it as much or as little as you want. The plugin will automatically detect your template and use it instead.

Please be aware, we do modify the base templates periodically, to add new features or make fixes. You will be responsible for keeping your custom templates up to date, but we will do our best to make it easy for you.

= Hey! The Single Staff Member View isn't quite matching my theme - what do I do? =

Our plugin supports creating your own templates, and it includes one by default for the Single Staff Member view.  If the Default HTML isn't right for you, go to the /staff-directory/templates/ folder and copy the file named "single-staff-member.php" to your Theme directory.  From there, you can modify this file as needed to get the HTML to work with your specific Theme.

= Do you support CSV import / export = 

Yes, but only in the Pro version.

== Screenshots ==

1. This is the Add New Staff Member Page.
2. This is the List of Staff Members - from here you can Edit or Delete a Staff Member.
3. This is an example of the Staff List being displayed on the 2014 WordPress theme.
4. This is the Staff List Widget.

== Changelog ==

= 1.7.4 =
* Updates CSV Import and Export to include photos.

= 1.7.3 =
* Add per_page parameter to staff_list shortcode and matching option to Staff List widget

= 1.7.2 =
* Fix: Make search_staff_members shortcode output in correct spot, not at start of post
* Fix: Disable Relevanssi on Staff Member searches to work around bug in Relevanssi

= 1.7.1 =
* Allow staff list to be sorted by any custom field

= 1.7 =
* Adds advanced search function and template

= 1.6.1 =
* Update widgets for WordPress 4.3 compatibility

= 1.6 =
* New Feature: Customizable Search Staff Template

= 1.5.1 =
* Fix: add widget file.

= 1.5 =
* Feature: Staff List widget
* Feature: Adds mailing address and website fields to Staff Members
* General: Clean up / extend staff-list and and single-staff-member-content templates

= 1.4.6 =
* Feature: Group staff members by category!
* Fix: Staff Member's first and last names were being auto-detected incorrectly. Fixed.

= 1.4.4 =
* Fix: Staff Member metadata had disappeared. Fixed.

= 1.4.3 =
* Fix: Default first and last name fields

= 1.4.2 =
* Add search form for staff members
* Allow toggling of whether staff members appear in normal search results

= 1.4.1 =
* Fix: change custom template name for single staff members

= 1.4 =
* Adds message to indicate detection of custom templates
* Adds CSV import/export (requires pro)

= 1.3.2 =
* Compatibility update; registration key fix.

= 1.3.1 =
* Updates Single Staff Member Template to render instead of display HTML

= 1.3 =
* Updates Single Staff Member Template to function better within your Theme.
* Fix double-encoding issue with Title field.

= 1.2.2 =
* Minor UI and Bug Fixes

= 1.2.1 =
* Minor Fixes

= 1.2 =
* Updates Readme and Documentation to better explain plugin use.
* Adds Taxonomies to Staff Members.
* Adds Shortcode to display an individual Staff Member.
* Various UI updates.

= 1.1.1 =
* Compatibility update for WP 4.1.1.

= 1.1 =
* Fix: address issue with incorrectly formatted e-mail link.

= 1.0 =
Initial Release!!

== Upgrade Notice ==

* 1.7.4: Updates CSV Import and Export to include photos.