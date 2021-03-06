<?php
/**
 *
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/4
 */

namespace app\modules\v1\controllers;

use common\filters\auth\HttpBasicParamAuth;
use common\filters\auth\HttpBasicTokenAuth;
use Yii;
use yii\base\Exception;
use yii\rest\Controller;
use yii\web\Response;
use app\modules\v1\managers\ProjectManager;

class ProjectController extends Controller {
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => HttpBasicParamAuth::className(),
            'tokenParam' => 'key'
        ];

        return $behaviors;
    }

    public function actionIndex() {
        $page = Yii::$app->request->get('page', 1);
        $pageSize = Yii::$app->request->get('pageSize', 10);

        $result = ProjectManager::instance()->all($page, $pageSize);
        $pagination = $result['pagination'];
        $projects = $result['projects'];

        Yii::$app->response->getHeaders()
            ->set('X-Pagination-Total-Count', $pagination['totalCount'])
            ->set('X-Pagination-Page-Count', $pagination['pageCount'])
            ->set('X-Pagination-Current-Page', $pagination['currentPage'])
            ->set('X-Pagination-Per-Page', $pagination['pageSize']);

        return $projects;
    }

    public function actionCreate() {
        $post = Yii::$app->request->post();
        $response = Yii::$app->getResponse();

        if (isset($post['name'])) {
            try {
                $result = ProjectManager::instance()->create($post);
                $response->setStatusCode(201);
                return array('id' => $result['id']);
            } catch (Exception $e) {
                $response->setStatusCode(500);
                $result = array(
                    'errmsg' => 'server error',
                );
            }
            return $result;
        } else {
            $response->setStatusCode(400);
            return array(
                'errmsg' => 'unexpected data',
            );
        }
    }

    public function actionView($id) {
        $response = Yii::$app->getResponse();
        $result = ProjectManager::instance()->view($id);
        if ($result) {
            return $result;
        } else {
            $response->setStatusCode(404);
        }
    }

    public function actionUpdate($id) {
        $post = Yii::$app->request->post();
        $response = Yii::$app->getResponse();
        try {
            $result = ProjectManager::instance()->update($id, $post);
            if ($result) {
                $response->setStatusCode(200);
                $response->getHeaders()->set('id', $result['id']);
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

    public function actionDelete($id) {
        $response = Yii::$app->getResponse();
        try {
            $result = ProjectManager::instance()->delete($id);
            if ($result) {
                $response->setStatusCode(204);
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
}