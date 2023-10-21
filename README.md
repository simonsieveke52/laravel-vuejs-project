# Starter Shop Overview

This repository designed to make it easier to get rolling with a Laravel eCommerce project.

## Coding styling & code quality
This project is pre configured to use [Laravel-PHP-CS-Fixer](https://github.com/stechstudio/Laravel-PHP-CS-Fixer), After any code edit, just run 

```bash
php artisan fixer:fix
```

I added some great tools inside this project. This will help us improve our code quality.

### Find bugs inside your code

For this task, we will use (larastan)[https://github.com/nunomaduro/larastan]
After any edits, run this command and check if it's all green.

```bash
 ./vendor/bin/phpstan analyse
```

Note: **phpstan.neon** is already configured. Just install the package.

## Features
* Based on Laravel 7
* Webpack pre configured and fully optimized
* Cachable HTML response
* Html blade components minified during compiling process
* New CMS Backend integrated (Voyager + all new features)
* Automated backups (daily at 00:00)
* Google Feed generator
* Payment with Authorize.net + Paypal
* Database seeders pre installed

## Updated elements
* Product main_image replaced with simple database column, so we can use it to save image path directly. If null, it fallback to **ProductImage** relation
* Added **status** column to all tables, so we can use **EnabledScope** as global scope

## Best practice
* Laravel best practices (Article here)[https://github.com/alexeymezenin/laravel-best-practices#contents].
* There are two logo versions, replace original files with your new project. (**public/images/logo.png && white-logo.png**).
* For CSS development, update primary color on your _variables.scss and recompile assets (primary color called **$highlight**). use **bootstrap theming** for fast CSS writing. [Read more.](https://getbootstrap.com/docs/4.0/getting-started/theming/).
* Use bootstrap **utilities** classes, [Read more.](https://getbootstrap.com/docs/4.0/utilities/borders/).
* On your local, Comment CSS purge plugin under **webpack.mix.js**.
* Try to use Fat models and skiny controllers as much as you can. (Check this snippet By Taylor Otwell)[https://blog.laravel.com/laravel-snippet-11] and (this article)[https://laraveldaily.com/taylor-otwell-thin-controllers-fat-models-approach/].

## Todo
* On navigation-primary.blade.php file, need to change categories listing to use <category-nav-item>.
* Update Discount base code.
* Refactor all routes, use conventional route actions (index, store...), remove routes like quick order, quick view and put them inside other controllers.
* Remove **InjectOrderData** middleware, replace it with simple localStorage data store. (store checkout form on native browser localStorage).
* Add cron for sitemap generation.

## Deploy to production
* Make sure to update **APP_ENV=** to production, This will enable cachable queries.
* Update **/resources/js/routes.js** baseUrl and baseDomain to your production url. In case new routes are added, generate the same file at same path. (package documentation here)[https://github.com/tightenco/ziggy]

```bash
php artisan ziggy:generate "resources/js/routes.js"
```

* Add Response cache middleware to **web.php**, replace this

```php
Route::group(['middleware' => []]
```

with 

```php
Route::group(['middleware' => ['cacheResponse']]
```

# Pre installed packages.

## Data imports

You can import data from files (Excel, CSV...) using data imports/exports

```bash
php artisan make:import {ImportClassName}
```

Navigate inside **ImportClassName** and add **Importable** Trait.

```php
class ImportClassName implements ToCollection
{
    use Importable;
```

Then define your import login inside **ImportClassName**

```php
/**
* @param Collection $collection
*/
public function collection(Collection $collection)
{
    // code
```

Next step is to create a seeder to call your import class. Inside the seeder, use this code snippet.

```php
(new ImportClassName)->import(
    storage_path('app/public/imports/{your_file_here}'),
    null,
    \Maatwebsite\Excel\Excel::CSV
);
```

Change file extension as you need. Refer to **\Maatwebsite\Excel\Excel** class

## Google Feed generator

Cron already enabled on kernel.php file. "fme:feed products" daily at 00:00,
just update the output on **ProductHandler**

```php
public function transform($product) : array
{
    // Update this function as you need.
}
```

# Notes

## Shared variables

You can check or define your shared variables inside The service provider **TemplateServiceProvider** located under **/app/Providers/TemplateServiceProvider**,
Best way to optimize shared variables is to use view composers, There are some files you can use them as example.

## Log order API's responses.

There is an object pre coded to log your API response. **OrderApiResponse**.
This is useful to track all API's responses. Inspired by Failed jobs class logic.

```php
$order->apiResponses()->create([
    'caller' => '{caller_class}',
    'content' => json_encode($apiResponse)
]);
```

## Allow user to checkout with "out of stock" products

Just set **force-checkout** attribute inside **default-variables** config file to true.