<?php

namespace app\components;

use yii\web\Response;

/**
 * Class ApiErrorHandler
 * @package app\components
 */
class ApiErrorHandler extends \yii\web\ErrorHandler
{
    public $filename = null;

    /**
     * @param \Exception $exception
     */
    protected function renderException($exception)
    {
        if (\Yii::$app->has('response')) {
            $response = \Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        $response->format = Response::FORMAT_JSON;
        $response->data = $this->convertExceptionToArray($exception);
        $response->setStatusCode(200);
        $this->logToFile($exception);

        if ($this->errorAction !== null) {
            \Yii::$app->runAction($this->errorAction);
        }

        $response->send();
    }

    /**
     * @inheritdoc
     */
    protected function convertExceptionToArray($exception)
    {
        return [
            'meta' => [
                'status' => 'error',
                'errors' => [['message' => $exception->getMessage()]]
            ]
        ];
    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    protected function logToFile($exception)
    {
        if ($this->filename !== null) {
            if (!is_readable($this->filename)) {
                throw new \ApiErrorHandlerException('ApiErrorHandler could not open log file');
            }
            $date = new \DateTime();
            $errorStr = $date->format('Y-m-d H:i:s') . ': ' . $exception->__toString() . PHP_EOL . PHP_EOL;
            file_put_contents($this->filename, $errorStr, FILE_APPEND);
        }
    }
}
