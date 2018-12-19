
/*****************       Tables  ***************************/

1. Users
2. Citiese
3. Branches
4. Package
5. PriceTable

/*************************/
/*        Users          */
/*************************/
1. id
2. name
3. email 
4. phone 
5. address
6. city_id
7. password 
8. token
9. created_at
10. updated_at
/************************/
/*        Citiese       */
/************************/

1. id
2. name 

/************************/
/*        Branches      */
/************************/

1. id 
2. name 
3. address 
4. city_id 
5. phone 

/***********************/
/*       Packages      */
/***********************/

1. id 
2. Sender Name 
3. Sender Phone
4. Sender Address 
5. Sender City

6. Receiver Name 
7. Receiver Phone 
8. Receiver Address 
9. Receiver City 

10. Package Weight
11. Status          // Picked Up , Scheduled , Arrived, Scheduled for Delivery , Delivered
12. Payment Type    // CAD (Cash On Delivery ), AP (Advance Payment)
13. Price
14. Pick Up Type    // Home, On Office 

15. source_branch_id
16. dest_branch_id
17. created_at
18. updated_at
/*************************/
/*      Price Table      */
/*************************/
1. id
2. description
3. price 

