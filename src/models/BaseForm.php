<?php

namespace dominus77\maintenance\models;

use Yii;
use yii\base\Model;
use dominus77\maintenance\interfaces\StateInterface;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use RuntimeException;
use Generator;

/**
 * Class BaseForm
 * @package dominus77\maintenance\models
 */
class BaseForm extends Model
{
    /**
     * Format datetime
     * @var string
     */
    protected $dateFormat;

    /**
     * @var StateInterface
     */
    protected $state;

    /**
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function init()
    {
        parent::init();
        $this->state = Yii::$container->get(StateInterface::class);
        $this->dateFormat = $this->state->dateFormat;
    }

    /**
     * Format datetime
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Prepare load model
     *
     * @param $path
     * @return array
     */
    public function prepareLoadModel($path)
    {
        $items = [];
        if ($contentArray = $this->getContentArray($path)) {
            foreach ($contentArray as $item) {
                $arr = explode(' = ', $item);
                if (isset($arr[0], $arr[1])) {
                    $items[$arr[0]] = $arr[1];
                }
            }
        }
        return $items;
    }

    /**
     * Return content to array this file
     *
     * @param $file string
     * @return array
     */
    protected function getContentArray($file)
    {
        $contents = $this->readTheFile($file);
        $items = [];
        foreach ($contents as $key => $item) {
            $items[] = $item;
        }
        return array_filter($items);
    }

    /**
     * Read generator
     *
     * @param $file
     * @return Generator
     */
    protected function readTheFile($file)
    {
        try {
            if (file_exists($file)) {
                $handle = fopen($file, 'rb');
                while (!feof($handle)) {
                    yield trim(fgets($handle));
                }
                fclose($handle);
            }
        } catch (RuntimeException $e) {
            throw new RuntimeException(
                "Failed to read $file file"
            );
        }
    }
}