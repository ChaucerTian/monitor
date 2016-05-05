<?php
/**
 * Project Manager
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/5
 */

namespace app\modules\v1\managers;


use Yii;
use app\models\Project;
use common\managers\Manager;

class ProjectManager extends Manager {
    /**
     * return instance of class
     * @param string $className
     * @return mixed
     */
    public static function instance($className = __CLASS__) {
        $instance = new $className();
        return $instance;
    }


    public function all($page=1, $pageSize=20) {
        $count = Yii::$app->db->createCommand('SELECT COUNT(1) FROM `project` WHERE 1')->queryScalar();

        $totalPage = intval(($count + $pageSize - 1) / $pageSize);
        $offset = ($page - 1) * $pageSize;

        $command = Yii::$app->db->createCommand('SELECT `id`, `name`, `description` as des,
            `contact`,  `location` FROM project WHERE 1 LIMIT :offset, :limit');
        $command->bindParam(':offset', $offset);
        $command->bindParam(':limit', $pageSize);
        $result = $command->queryAll();

        foreach ($result as &$item) {
            $item['location'] = json_decode($item['location'], true);
        }

        return array(
            'projects' => $result,
            'pagination' => array(
                'totalCount' => $count,
                'pageSize' => $pageSize,
                'pageCount' => $totalPage,
                'offset' => $offset,
                'currentPage' => $page,
            ),
        );
    }

    /**
     * create a new project
     * @param $content array post content
     * @return array array('id' => 'xx')
     * @throws ServerErrorHttpException  then fail to save
     */
    public function create($content) {
        $project = new Project();
        $project->name =  $content['name'];
        $project->description = $content['des'];
        $project->contact = $content['contact'];
        $project->location = json_encode($content['location']);
        $project->create_time = date('Y-m-d H:i:s', time());
        if ($project->save()) {
            return array('id' => $project->id);
        } else {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    /**
     * return a project's attributes as array
     * @param $id
     * @return array|null
     */
    public function view($id) {
        $project = Project::findOne(['id' => $id]);
        if ($project) {
            return array(
                'id' => $project->id,
                'name' => $project->name,
                'des' => $project->description,
                'location' => json_decode($project->location, true),
            );
        } else {
            return null;
        }
    }
}