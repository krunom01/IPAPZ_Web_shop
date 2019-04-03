

UserController:



function profile() - User can see his data.

editProfile() - User can change his data (firstname, lastname, email).

changePassword() - User can change his password.

userWishList() - In Products details, User can add Product in Wishlist.

userOrders() - User's Orders.

userOrderDetails() - User can see his order with all information (list of products, product quantity, total price).

editUser() - admin can change User data (but not password).



AdminController : 


users() - list of all Users in App with pagination.

showOrder() - Order with id where Admin can see ordered Items.

updateOrder() - admin updates Order from New to Paid.

deleteOrder() - admin deletes order 

customPages() - list of Custom pages in App.

customPagesNew() - admin creates new Custom page.

customPageEdit() - admin edits Custom page.

customPageDelete () - admin deletes Custom page.

coupons() - list of Coupons.

strRandom() - Coupon code, random generate number.

deleteCoupon() - admin deletes Coupon.

adminOrders() -  list of all Orders. Orders can be filtrated by user name, email or date.

createPdf() - admin can create PDF file (invoice Order).

downloadPdf() - download Order in PDF.

payments() - list of all Payment methods in App.

newPayment() - admin creates new Payment method (by default visibility is set to 1).

editPayment() - payment update

deletePayment() - delete payment

updatePayment() - visibility can be set to 1 or 0. If it is 0, Users can't use this Payment mathod.

newCsv() - Upload CSV file with Shipping Countries, Code and Shipping price for each Country.

newShippingCountry() - admin creates new Shipping Country.

shippingCountries() - list of all Shipping Countries (admin panel).

deleteCountry() - admin deletes Shipping Country.

editCountry() -  admin Updated Shipping Country.


HomeController:


showProduct() - Product details with custom URL.

userShopCart - User's ShopCart with Items. 

deleteItemFromShopCart() - User can delete Items from Cart.

userNewOrder() - User create new Order with his Address, Country and total price. Use can have many Orders.

userOrderConfirm - User confirm his Order by choosing payment method ,invoice of paypal. User can dd coupons for 
total price discount.

showCustomPage() - Details of Custom page

paypal() - Paypal transaction with gateway function










