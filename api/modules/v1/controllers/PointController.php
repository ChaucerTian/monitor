<?php
/**
 * Point Controller
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/6
 */
namespace app\modules\v1\controllers;

use app\models\Point;
use Yii;
use yii\base\Exception;
use yii\rest\Controller;
use yii\web\Response;
use common\filters\auth\HttpBasicParamAuth;
use app\modules\v1\managers\PointManager;

class PointController extends Controller {
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => HttpBasicParamAuth::className(),
            'tokenParam' => 'key'
        ];

        return $behaviors;
    }

    public function actionIndex($projectId, $deviceId) {
        $page = Yii::$app->request->get('page', 1);
        $pageSize = Yii::$app->request->get('pageSize', 10);

        $result = PointManager::instance()->all($projectId, $deviceId, $page, $pageSize);
        $pagination = $result['pagination'];
        $points = $result['points'];

        Yii::$app->response->getHeaders()
            ->set('X-Pagination-Total-Count', $pagination['totalCount'])
            ->set('X-Pagination-Page-Count', $pagination['pageCount'])
            ->set('X-Pagination-Current-Page', $pagination['currentPage'])
            ->set('X-Pagination-Per-Page', $pagination['pageSize']);

        return $points;
    }

    public function actionCreate($projectId, $deviceId) {
        $post = Yii::$app->request->post();
        $response = Yii::$app->getResponse();

        if (isset($post['name'])) {
            try {
                $result = PointManager::instance()->create($projectId, $deviceId);
                $response->setStatusCode(201);
                return array('id' => $result['id']);
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

    public function actionUpdate($projectId, $deviceId, $id) {
        $post = Yii::$app->request->post();
        $response = Yii::$app->getResponse();

        try {
            $result = PointManager::instance()->update($projectId, $id, $post);
            if ($result) {
                $response->setStatusCode(200);
                $response->getHeaders()
                    ->set('id', $result['id'])
                    ->set('project_id', $projectId)
                    ->set('device_id', $deviceId);
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

    public function actionView($projectId, $deviceId, $id) {
        $response = Yii::$app->getResponse();
        $start = Yii::$app->request->get('start', false);
        if ($start) {
            $result = PointManager::instance()->view($id);
        } else {
            $result = PointManager::instance()->viewValue($id);
        }
        if ($result) {
            return $result;
        } else {
            $response->setStatusCode(404);
        }
    }


    public function actionTrend($projectId, $deviceId, $id) {
        $response = Yii::$app->getResponse();
        $start = Yii::$app->request->get('start', false);
        $end = Yii::$app->request->get('end', date('Y-m-d H:i:s', time()));
        $interval = Yii::$app->request->get('interval', 1);
        $page = Yii::$app->request->get('page', 1);
        $pageSize = Yii::$app->request->get('pageSize', 100);

        $result = PointManager::instance()->viewTrend($id, $start, $end, $interval, $page, $pageSize);
        $pagination = $result['pagination'];
        $data = $result['data'];

        Yii::$app->response->getHeaders()
            ->set('X-Pagination-Total-Count', $pagination['totalCount'])
            ->set('X-Pagination-Page-Count', $pagination['pageCount'])
            ->set('X-Pagination-Current-Page', $pagination['currentPage'])
            ->set('X-Pagination-Per-Page', $pagination['pageSize']);

        return $data;
    }
}