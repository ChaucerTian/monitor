<?php
/**
 * Device Controller
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/6
 */

namespace app\modules\v1\controllers;

use app\models\Device;
use app\modules\v1\managers\DeviceManager;
use Yii;
use yii\base\Exception;
use yii\rest\Controller;
use yii\web\Response;
use common\filters\auth\HttpBasicParamAuth;

class DeviceController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => HttpBasicParamAuth::className(),
            'tokenParam' => 'key'
        ];

        return $behaviors;
    }

    public function actionIndex($projectId) {
        $page = Yii::$app->request->get('page', 1);
        $pageSize = Yii::$app->request->get('pageSize', 10);

        $result = DeviceManager::instance()->all($projectId, $page, $pageSize);
        $pagination = $result['pagination'];
        $devices = $result['devices'];

        Yii::$app->response->getHeaders()
            ->set('X-Pagination-Total-Count', $pagination['totalCount'])
            ->set('X-Pagination-Page-Count', $pagination['pageCount'])
            ->set('X-Pagination-Current-Page', $pagination['currentPage'])
            ->set('X-Pagination-Per-Page', $pagination['pageSize']);

        return $devices;
    }

    public function actionCreate($projectId) {
        $post = Yii::$app->request->post();
        $response = Yii::$app->getResponse();

        if (isset($post['program_version']) && isset($post['name'])) {
            try {
                $result = DeviceManager::instance()->create($projectId, $post);
                $response->setStatusCode(201);
                return array(
                    'id' => $result['id'],
                );
            } catch (Exception $e) {
                $response->setStatusCode(500);
                return array(
                    'errmsg' => 'server error',
                );
            }
        } else {
            $response->setStatusCode(400);
            return array(
                'errmsg' => 'unexpected data',
            );
        }
    }

    public function actionView($projectId, $id) {
        $response = Yii::$app->getResponse();
        $result = DeviceManager::instance()->view($projectId, $id);
        if ($result) {
            return $result;
        } else {
            $response->setStatusCode(404);
        }
    }

    public function actionUpdate($projectId, $id) {
        $post = Yii::$app->request->post();
        $response = Yii::$app->getResponse();
        try {
            $result = DeviceManager::instance()->update($projectId, $id, $post);
            if ($result) {
                $response->setStatusCode(200);
                $response->getHeaders()
                    ->set('id', $result['id'])
                    ->set('project_id', $projectId);
            } else {
                $response->setStatusCode(404);
            }
        } catch (Exception $e) {
            $response->setStatusCode(500);
            return array(
                'errmsg' => 'server error',
            );
        }
    }

    public function actionDelete($projectId, $id) {
        $response = Yii::$app->getResponse();
        try {
            $result = DeviceManager::instance()->delete($projectId, $id);
            if ($result) {
                $response->setStatusCode(204);
            } else {
                $response->setStatusCode(404);
            }
        } catch (Exception $e) {
            $response->setStatusCode(500);
            return array('errmsg' => 'server error');
        }
    }

}