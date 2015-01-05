<?php
namespace yii2mod\cron\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "cron_schedule".
 *
 * @property string $id
 * @property string $job_code
 * @property string $status
 * @property string $messages
 * @property string $date_created
 * @property string $date_scheduled
 * @property string $date_executed
 * @property string $date_finished
 */
class CronScheduleModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cron_schedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['messages'], 'string'],
            [['date_created', 'date_scheduled', 'date_executed', 'date_finished'], 'safe'],
            [['job_code'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cron', 'ID'),
            'jobCode' => Yii::t('cron', 'Job Code'),
            'status' => Yii::t('cron', 'Status'),
            'messages' => Yii::t('cron', 'Messages'),
            'date_created' => Yii::t('cron', 'Date Created'),
            'date_scheduled' => Yii::t('cron', 'Date Scheduled'),
            'date_executed' => Yii::t('cron', 'Date Executed'),
            'date_finished' => Yii::t('cron', 'Date Finished'),
        ];
    }


    /**
     *
     * @author   Roman Protsenko <protsenko@zfort.com>
     *
     * @param string $jobCode
     * @param string $status
     * @param null   $messages
     *
     * @internal param string $message
     * @return boolean
     */
    public function startCronSchedule($jobCode, $status = null, $messages = null)
    {
        if ($status === null) {
            $status = 'running';
        }
        $this->job_code = $jobCode;
        $this->status = $status;
        $this->messages = $messages;

        $this->date_scheduled = new Expression('NOW()');
        $this->date_executed = new Expression('NOW()');
        return $this->save();
    }

    /**
     *
     * @author   Roman Protsenko <protsenko@zfort.com>
     *
     * @param string $status
     * @param null   $messages
     *
     * @internal param string $message
     * @return boolean
     */
    public function endCronSchedule($status, $messages = null)
    {
        if ($this->id) {
            $this->date_finished = new Expression('NOW()');
            $this->status = $status;
            $this->messages = $messages;
            return $this->save();
        }
        return false;
    }
}



