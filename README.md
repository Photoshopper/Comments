# Comments module for AsgardCMS 2

## Installation

1. Put a module in "Modules" folder
2. Run commands:
`php artisan module:update comments`
`php artisan module:migrate comments`
`php artisan module:publish comments`
`php artisan vendor:publish --provider="Greggilbert\Recaptcha\RecaptchaServiceProvider"`
3. Add `RECAPTCHA_PUBLIC_KEY` and `RECAPTCHA_PRIVATE_KEY` in `.env` file
4. Give permissions to the module.

## Usage

{!! Comment::render($model) !}}`

You can change max depth of a comment tree by passing an extra parameter (by default: 3)

`{!! Comment::render($model, 5) !!}`

## Methods

`{!! Comment::count($model) !!}` - return a number of comments in a model
`{!! Comment::getUsername($user_id) !!}` - return user's full name
