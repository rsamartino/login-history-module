M2 module that records a customer's login history and displays on the frontend in various ways with some functionality to sort, delete and export records.

Adding the table was a relatively straightforward implementation of Magento 2's CRUD / repository pattern, if time consuming due to all of the classes involved. I did choose to use etc/db_schema.xml to define the table, so this module is only compatible with > Magento 2.3.

Adding information to the account dashboard was also straightforward; things got interesting when displaying the login history table. I knew that Magento's uiComponent grid contains all the needed functionality, but these can be a pain to configure, and I wasn't sure if they would work on the frontend, since they usually appear in the admin. This seemed like the best approach though, and it wasn't too hard to get the frontend functionality I wanted. One issue was the admin styles were not present, but I found someone who pulled out the relevant styles and made those available [here](https://belvg.com/blog/ui-grid-component-on-the-front-end-in-magento-2.html), so this made the grid basically presentable/functional.

The uiComponent made implementing the record delete functionality a lot easier. It also made adding an 'export to csv' button (relatively) easy as well. 
