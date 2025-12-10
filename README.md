# Cube Wiki API – Multitenant Knowledge Management Platform
Welcome to Cube Wiki, a multitenant knowledge-management platform built with Filament.
Cube Wiki offers a structured way to manage documentation through Applications, Categories, and Pages.

[//]: # (## Showcase)

[//]: # (### User)

[//]: # (![User Dashboard]&#40;Docs/images/dashboarduser.png&#41;)

[//]: # (![User create tenant]&#40;Docs/images/createtenant.png&#41;)

[//]: # (![User application]&#40;Docs/images/appuser.png&#41;)

[//]: # (![User application]&#40;Docs/images/catuser.png&#41;)

[//]: # (![User application]&#40;Docs/images/pageuser.png&#41;)

## Introduction
Cube Wiki is a multitenant knowledge-management platform built with Filament.
The system has two distinct roles: **Owner**, and **Member**. Users with the **Owner** role have full control over their tenant,
including creating users. Users with the **Member** role
can create and manage applications, categories, and pages. Every page is written in Markdown and stored in the Cube Wiki API.

## Features
### Multitenancy
- Each user can belong to one or multiple tenants.
- All data is fully isolated per tenant.
- Tenants can create new tenants or switch between existing one if you joined them.
- Users are put on selected tenants.
### Tenant features
- Create tenants - each tenant receives its own API token with Sanctum.
- Create and manage users.
- Assign users to one or multiple tenants.
### Users features
- Create and edit applications, categories and pages inside their tenants.
- Write pages using the build in Markdown editor.
- Switch between tenants if their in more than one.

## Applications, Categories and Pages
### Applications
- Create applications with a name — slugs are generated automatically.
- Applications act as the top level container for documentation
```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Application extends Model
{
    use HasSlug;
    //slug options

    protected $fillable = ['tenant_id','name','slug'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenants::class, 'tenant_id', 'id');
    }
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
```
### Categories
- Create categories with an auto-generated slug.
- Each category must be linked to an application (selectable via dropdown).
```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug;
    //slug options

    protected $fillable = ['tenant_id', 'application_id', 'name', 'slug'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenants::class, 'tenant_id', 'id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

}

```
### Pages
- Create pages with auto-generated slugs.
- Each page must be linked to a category
- Markdown editor for writing content.
- Markdown is stored as is and served via the API.
```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasSlug;
    //slug options

    protected $table = 'pages';

    protected $fillable = ['tenant_id','category_id','title','slug','content'];


    public function tenant(): BelongsTo 
    { 
        return $this->belongsTo(Tenants::class, 'tenant_id', 'id'); 
    }

    public function category(): BelongsTo 
    { 
        return $this->belongsTo(Category::class, 'category_id', 'id'); 
    }

    protected static function booted(): void
    {
        static::saving(function (Page $page) {
            if ($page->category_id && !$page->tenant_id) {
                $page->tenant_id = Category::query()->whereKey($page->category_id)->value('tenant_id');
            }
        });
    }
}

```
## Slug generation
Slug is automatically generated from the name of the application, category or page with
Spatie Sluggable package. When you create an application, category or page the slug will stay
So if you edit the name the slug will not change. So the links to the pages will not break.

Slug is used in the API for a cleaner URL structure.

#### Example of slug generation in a Model:
```php
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use HasSlug;

public function getSlugOptions(): SlugOptions    {
     return SlugOptions::create()
         ->generateSlugsFrom('name')
         ->saveSlugsTo('slug')
         ->slugsShouldBeNoLongerThan(64)
         ->doNotGenerateSlugsOnUpdate();
     }
```
## Markdown Pages
### Tables support
You can use regular Markdown syntax to create tables in your pages.
```markdown
| Syntax     |             Description (center)              |     Foo (right) | Bar (left)      |
|------------|:---------------------------------------------:|----------------:|:----------------|
| Header     |                     Title                     |       Something | Else            |
| Paragraphs |  First paragraph. <br><br> Second paragraph.  | First paragraph | First paragraph |
```
![Table Example](Docs/images/table.png)



## API overview
Each tenant receives one API token, generated by admin.
This token provides access to all data belonging to that tenant.
The API returns the complete hierarchical structure:

Tenant → Applications → Categories → Pages
#### Example API responce
```json
{
  "tenant": {
    "id": 1,
    "name": "Cube",
    "slug": "cube"
  },
  "applications": [
    {
      "id": 10,
      "tenant_id": 1,
      "name": "Hint",
      "slug": "hint",
      "categories": [
        {
          "id": 9,
          "tenant_id": 1,
          "application_id": 10,
          "name": "HintActions",
          "slug": "hintactions",
          "pages": [
            {
              "id": 9,
              "category_id": 9,
              "tenant_id": 1,
              "title": "Slug",
              "slug": "slug",
              "content_html": "\u003Ch1\u003EWhat is a slug?\u003C/h1\u003E\n\u003Cp\u003EThis is a slug\u003C/p\u003E\n"
            }
          ]
        }
      ]
    }
  ]
}
```
The content_html might look messy, but it is just the HTML generated from the Markdown content.
In the markdown content it is just this from the page:
#### Example of page content in Markdown:
```markdown
# What is a slug?
this is a slug
```
## Package integration
Cube Wiki can be easily integrated into other Laravel/Filament projects
using the companion package

[CubeWikiPackage](https://github.com/TerpDev/CubeWikiPackage)

You can see the README.md of that package for more information about the integration.
## Summary
Cube Wiki is flexible and scalable multitenant wiki System with:
- Applications → Categories → Pages structure
- Markdown support for the pages
- API access per tenant
- Automatic slug generation

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- Spatie - Sluggable package is used for slug generation
  of [Spatie's Sluggable ](https://github.com/spatie/laravel-sluggable)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
