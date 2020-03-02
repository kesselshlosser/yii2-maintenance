<?php

namespace dominus77\maintenance\actions\frontend;

use Yii;
use yii\base\Action;
use Exception;
use dominus77\maintenance\interfaces\StateInterface;
use dominus77\maintenance\states\FileState;
use dominus77\maintenance\models\SubscribeForm;
use dominus77\maintenance\BaseMaintenance;

/**
 * Class IndexAction
 * @package dominus77\maintenance\actions\frontend
 *
 * @property array $viewRenderParams
 */
class IndexAction extends Action
{
    /** @var string */
    public $defaultName;

    /** @var string */
    public $defaultMessage;

    /** @var string */
    public $layout;

    /** @var string */
    public $view;

    /** @var array */
    public $params = [];

    /**
     * @var StateInterface
     */
    protected $state;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->state = Yii::$container->get(StateInterface::class);

        if ($this->defaultMessage === null) {
            $this->defaultMessage = BaseMaintenance::t('app', $this->state->getParams(FileState::MAINTENANCE_PARAM_CONTENT));
        }

        if ($this->defaultName === null) {
            $this->defaultName = BaseMaintenance::t('app', $this->state->getParams(FileState::MAINTENANCE_PARAM_TITLE));
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function run()
    {
        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }
        return $this->controller->render($this->view ?: $this->id, $this->getViewRenderParams());
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getViewRenderParams()
    {
        $model = new SubscribeForm();
        return [
            'name' => $this->defaultName,
            'message' => $this->defaultMessage,
            'model' => $model,
        ];
    }
}