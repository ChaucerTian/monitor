<?php
/**
 * Device Manager
 * Author: Chaucer Tian (tqignshuai@gmail.com)
 * Date: 2016/5/6
 */

namespace app\modules\v1\managers;

use Yii;
use app\models\Device;
use common\managers\Manager;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;

class DeviceManager extends Manager {
    /**
     * @inheritdoc
     */
    public static function instance($className = __CLASS__) {
        $instance = new $className();
        return $instance;
    }


    /**
     * return device list of a project with pagination
     * @param $projectId
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function all($projectId, $page=1, $pageSize=20) {
        $count = Yii::$app->db->createCommand('SELECT COUNT(1) FROM device
            WHERE project_id=:project_id')->bindParam(':project_id', $projectId)->queryScalar();

        $totalPage = intval(($count + $pageSize - 1) / $pageSize);
        $offset = ($page - 1) * $pageSize;

        $command = Yii::$app->db->createCommand('SELECT id, project_id, program_version, name, amount,
            address, type, status FROM device WHERE project_id=:project_id LIMIT :offset, :limit');
        $command->bindParam(':project_id', $projectId);
        $command->bindParam(':offset', $offset);
        $command->bindParam(':limit', $pageSize);
        $result = $command->queryAll();

        return array(
            'devices' => $result,
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
     * create a device
     * @param $projectId
     * @param $content
     * @return array
     * @throws ServerErrorHttpException
     */
    public function create($projectId, $content) {
        $device = new Device();
        $device->project_id = $projectId;
        $device->program_version = $content['program_version'];
        $device->name = $content['name'];
        $device->address = $content['address'];
        $device->status = isset($content['status']) ? $content['status'] : Device::STATUS_NORMAL;
        $device->alarm_status = isset($content['status']) ? $content['status'] : Device::ALARM_STATUS_NORMAL;

        if ($device->save()) {
            return array('id' => $device->id);
        } else {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    /**
     * view a device
     * @param $projectId
     * @param $id
     * @return array|null
     */
    public function view($projectId, $id) {
        $device = Device::findOne(['id' => $id, 'project_id' => $projectId]);
        if ($device) {
            return array(
                'id' => $device->id,
                'type' => $device->type,
                'name' => $device->name,
                'address' => $device->address,
                'program_version' => $device->program_version,
                'status' => $device->status,
                'alarm_status' => $device->alarm_status,
                'last_update' => strtotime($device->update_time),
            );
        } else {
            return null;
        }
    }

    public function update($projectId, $id, $content) {
        $device = Device::findOne(['project_id' => $projectId, 'id' => $id]);
        if (!$device) {
            return false;
        }
        $device->type = isset($content['type']) ? $content['type'] : $device->type;
        $device->name = isset($content['name']) ? $content['name'] : $device->name;
        $device->address = isset($content['address']) ? $content['address'] : $device->address;
        $device->program_version = isset($content['program_version']) ? $content['program_version']
            : $device->program_version;
        $device->status = isset($content['status']) ? intval($content['status']) : $device->status;
        $device->alarm_status = isset($content['alarm_status']) ? intval($content['alarm_status'])
            : $device->alarm_status;

        if ($device->save()) {
            return array(
                'project_id' => $device->project_id,
                'id' => $device->id,
            );
        } else {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    /**
     * delete a device
     * @param $projectId
     * @param $id
     * @return bool
     * @throws Exception
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function delete($projectId, $id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $command = Yii::$app->db->createCommand('DELETE FROM device WHERE project_id=:project_id and id=:id');
            $command->bindParam(':id', $id);
            $command->bindParam(':project_id', $projectId);
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