<?php
/**
 * Point Manager
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/6
 */

namespace app\modules\v1\managers;

use app\models\Device;
use app\models\Point;
use app\models\Trend;
use common\managers\Manager;
use Yii;

class PointManager extends Manager {
    /**
     * return instance of class
     * @param string $className
     * @return mixed
     */
    public static function instance($className = __CLASS__) {
        $instance = new $className();
        return $instance;
    }

    public function all($projectId, $deviceId, $page=1, $pageSize=20) {
        $command = Yii::$app->db->createCommand('SELECT COUNT(1) FROM point
            WHERE project_id=:project_id AND device_id=:device_id');
        $command->bindParam(':device_id', $deviceId);
        $command->bindParam(':project_id', $projectId);
        $count = $command->queryScalar();

        $totalPage = intval(($count + $pageSize - 1) / $pageSize);
        $offset = ($page - 1) * $pageSize;

        $command = Yii::$app->db->createCommand('SELECT id, type, status, `timestamp`, value
            FROM point WHERE project_id=:project_id AND device_id=:device_id LIMIT :offset, :limit');
        $command->bindParam(':project_id', $projectId);
        $command->bindParam(':device_id', $deviceId);
        $command->bindParam(':offset', $offset);
        $command->bindParam(':limit', $pageSize);
        $result = $command->queryAll();

        foreach ($result as &$item) {
            $item['timestamp'] = date('c', strtotime($item['timestamp']));
        }

        return array(
            'points' => $result,
            'pagination' => array(
                'totalCount' => $count,
                'pageSize' => $pageSize,
                'pageCount' => $totalPage,
                'offset' => $offset,
                'currentPage' => $page,
            ),
        );
    }

    public function create($projectId, $deviceId, $content) {
        $point = new Point();
        $point->project_id = $projectId;
        $point->device_id = $deviceId;
        $point->type = $content['type'];
        $point->value = $content['value'];
        $point->status = isset($content['status']) ? intval($content['status']) : 0;
        $point->timestamp = strtotime($content['timestamp']);
        $point->create_time = date('Y-m-d H:i:s', time());
        if ($point->save()) {
            $trend = new Trend();
            $trend->point_id = $point->id;
            $trend->value = $content['value'];
            $trend->timestamp = date("Y-m-d H:i:s", strtotime($content['timestamp']));
            $trend->save();
            return array('id' => $point->id);
        } else {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    public function update($projectId, $deviceId, $id, $content) {
        $point = Point::findOne(array(
            'id' => $id,
            'project_id' => $projectId,
            'device_id' => $deviceId,
        ));

        if (!$point) {
            return false;
        }
        $point->type = isset($content['type']) ? intval($content['type']) : $point->type;
        $point->status = isset($content['status']) ? intval($content['status']) : $point->status;
        $point->timestamp = isset($content['timestamp']) ? date("Y-m-d H:i:s", strtotime($content['timestamp']))
            : date("Y-m-d H:i:s", strtotime($point->timestamp));
        $point->value = isset($content['value']) ? $content['value'] : $point->value;

        if ($point->save()) {
            if (isset($content['value']) && isset($content['timestamp'])) {
                $trend = new Trend();
                $trend->point_id = $point->id;
                $trend->timestamp = date('Y-m-d H:i:s', strtotime($content['timestamp']));
                $trend->value = $content['value'];
                $trend->save();
            }
            return array('id' => $point->id);
        } else {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    public function view($id) {
        $point = Point::findOne(['id' => $id]);
        if ($point) {
            return array(
                'id' => $point->id,
                'type' => $point->type,
                'value' => $point->value,
                'timestamp' => date('c', strtotime($point->timestamp)),
            );
        } else {
            return null;
        }
    }

    public function viewValue($id) {
        $point = Point::findOne(['id' => $id]);
        if ($point) {
            return array(
                'value' => $point->value,
                'timestamp' => date('c', strtotime($point->timestamp)),
            );
        } else {
            return null;
        }
    }

    public function viewTrend($id, $start, $end, $interval, $page=1, $pageSize=100) {
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));
        $first = Yii::$app->db->createCommand('SELECT * FROM trend WHERE timestamp > :start
            ORDER BY `timestamp` ASC LIMIT 1')->bindParam(':start', $start)->queryOne();
        if (!$first) {
            $result = null;
            $totalPage = 0;
            $offset = 0;
            $count = 0;
        } else {
            $startTimestamp = $first['timestamp'];
            $command = Yii::$app->db->createCommand('SELECT COUNT(1) FROM (SELECT `id` FROM trend
                WHERE `timestamp`>=:start and `timestamp`<=:end and point_id=:id
                AND (`timestamp`-:start)%:interval=0 GROUP BY `timestamp` ORDER BY `timestamp` ASC) as temp');
            $command->bindParam(':start', $startTimestamp);
            $command->bindParam(':end', $end);
            $command->bindParam(':id', $id);
            $command->bindParam(':interval', $interval);
            $count = $command->queryScalar();
            $totalPage = intval(($count + $pageSize - 1) / $pageSize);
            $offset = ($page - 1) * $pageSize;

            $command = Yii::$app->db->createCommand('SELECT `timestamp`, `value` FROM trend
                WHERE `timestamp`>=:start and `timestamp`<=:end and point_id=:id
                AND (`timestamp`-:start)%:interval=0 GROUP BY `timestamp` ORDER BY `timestamp` ASC
                LIMIT :offset, :limit');
            $command->bindParam(':start', $startTimestamp);
            $command->bindParam(':end', $end);
            $command->bindParam(':id', $id);
            $command->bindParam(':interval', $interval);
            $command->bindParam(':offset', $offset);
            $command->bindParam(':limit', $pageSize);
            $result = $command->queryAll();
        }
        return array(
            'data' => $result,
            'pagination' => array(
                'totalCount' => $count,
                'pageSize' => $pageSize,
                'pageCount' => $totalPage,
                'offset' => $offset,
                'currentPage' => $page,
            ),
        );
    }

    public function delete($projectId, $deviceId, $id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $command = Yii::$app->db->createCommand('DELETE FROM point WHERE project_id=:project_id
                and device_id=:device_id and id=:id');
            $command->bindParam(':id', $id);
            $command->bindParam(':project_id', $projectId);
            $command->bindParam(':device_id', $deviceId);
            $result = $command->execute();
            if ($result != 1) {
                $transaction->rollBack();
                return false;
            } else {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}