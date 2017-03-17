# ApiErrorHandler
Simple customized Yii2 ErrorHandler handles uncaught PHP errors and exceptions.

### How to use
Add new section (or update existing one) to the components
```
'components' => [
  'errorHandler' => [
    'class' => 'ApiErrorHandler\ApiErrorHandler',
    'filename' => 'error.log',                //optional, if specified the trace will be written to the log file
    'errorAction' => 'controler/actionError'  //optional, if specified the error action will be run
  ]
]
```
