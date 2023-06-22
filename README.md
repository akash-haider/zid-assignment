## Backend Assignment

## Task
You were given a sample [Laravel][laravel] project which implements sort of a personal wishlist
where user can add their wanted products with some basic information (price, link etc.) and
view the list.

#### Refactoring
The `ItemController` is messy. Please use your best judgement to improve the code. Your task
is to identify the imperfect areas and improve them whilst keeping the backwards compatibility.

#### New feature
Please modify the project to add statistics for the wishlist items. Statistics should include:

- total items count
- average price of an item
- the website with the highest total price of its items
- total price of items added this month

The statistics should be exposed using an API endpoint. **Moreover**, user should be able to
display the statistics using a CLI command.

Please also include a way for the command to display a single information from the statistics,
for example just the average price. You can add a command parameter/option to specify which
statistic should be displayed.

## Open questions
Please write your answers to following questions.

> **Please briefly explain your implementation of the new feature**

**I have modified the project to include the following statistics for the items:**

1. Total Items Count:
    - Description: Displays the total count of items.
####
2. Average Price of an Item:
    -  Description: Calculates and shows the average price of an item.
####
3. Website with the Highest Total Price of its Items:

   `Here I have shown the following 2 outputs because I was in doubt with the statement.`
   ####
     - 3.1: The method `getWebsiteWithHighestPrice` returns the URL with the highest price.
     ####
     - 3.2: The method `getWebsiteWithHighestTotalPriceItems` separates the domain/website from the URL field, sums the total price of its all items, and returns the domain/website with the highest total price.
####
4. Total Price of Items Added This Month:
    - Description: Computes the sum of the prices of items added during the current month.
## Usage

#### API Endpoints

The project provides the following API endpoints:

- `GET /statistics`: Shows all statistics.
- `GET /statistics/total_items`: Displays the total count of items.
- `GET /statistics/average_price`: Calculates and shows the average price of an item.
- `GET /statistics/website_highest_price`: Shows the URL with the highest price.
- `GET /statistics/website_highest_total_price_items`: Shows the website with the highest total price of its items.
- `GET /statistics/total_price_current_month`: Shows the sum of the prices of items added during the current month.

#### CLI Commands

The project also includes some helpful CLI commands:

- `php artisan display:statistics`: Shows all statistics in table format.
- `php artisan display:statistics total_items`: Displays the total count of items.
- `php artisan display:statistics average_price`: Calculates and shows the average price of an item.
- `php artisan display:statistics website_highest_price`: Shows the URL with the highest price.
- `php artisan display:statistics website_highest_total_price_items`: Shows the website with the highest total price of its items.
- `php artisan display:statistics total_price_current_month`: Shows the sum of the prices of items added during the current month.



> **For the refactoring, would you change something else if you had more time?**
>> Continuous improvement is essential in code development, as there is always an opportunity to enhance your code further.
 ###Approach I have used to refactor ItemController code.
 **Based on the current ItemController code, here are some areas that can be improved in the ItemController:**
 - Separate validation rules into reusable variables: The validation rules for store and update methods are identical. To avoid duplication and improve code maintainability, you can extract the validation rules into separate variables and reuse them in both methods.
 - Move the CommonMarkConverter instantiation to a separate method: The CommonMarkConverter is used in both the store and update methods. Instead of duplicating the code, you can create a separate method to instantiate the converter and reuse it in both methods.
 - Simplify JSON response creation: The creation of JSON responses can be simplified using Laravel's response() helper function instead of explicitly creating JsonResponse instances. This change simplifies the code and improves readability.
 - Remove unnecessary findOrFail calls: In the show and update methods, the findOrFail method is used to retrieve the Item model. Instead, you can use the find method, which returns null if the item is not found, and handle the case separately if needed.
 - Changed $request->get to $request->input for 2 reasons: First is the recommended usage is ->input() as it is a little bit more powerfull because it can parse nested data more fluently. Secondly, I prefer to write $request->input :)

## Running the project
This project requires a database to run. For the server part, you can use `php artisan serve`
or whatever you're most comfortable with.

You can use the attached DB seeder to get data to work with.

#### Running tests
The attached test suite can be run using `php artisan test` command.

[laravel]: https://laravel.com/docs/8.x
