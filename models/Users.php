<?php

namespace app\models;

use app\modules\admin\models\AuthAssignment;
use app\modules\admin\models\RedirectUrlList;
use app\modules\hr\models\HrEmployeeRelUsers;
use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property string|null $username
 * @property string|null $password
 * @property string|null $auth_key
 * @property int|null $status_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property StatusList $status
 * @property HrEmployeeRelUsers $hrEmployees
 */
class Users extends BaseModel implements \yii\web\IdentityInterface
{

    /**
     * @var string
     * Password yangi foydalanuvchi yaratishda majburiy qilish uchun
     */
    const SCENARIO_CREATE = "scenario-create";

    /**
     * @var bool
     * Foydalanuvchi malumotlari yangilanayotgan bo'lsa: true bo'ladi
     */
    public $isUpdate = false;

    /**
     * @var string
     * Hr employee bog'lash uchun
     */
    public $hr_employee_id;

    /**
     * @var string
     * Takroriy password uchun
     */
    public $password_repeat;

    /**
     * @var
     * Foydalanuchiga rolar biriktirish uchun
     */
    public $roles;

    /**
     * @var string
     * Bo'limlar nomini chiqarish uchun
     */
    public $hr_deparment_name;

    /**
     * @var string
     * Telefon raqamini chiqarish uchun
     */
    public $phone_number;

    /**
     * @var string
     * Elektron pochta chiqarish uchun
     */
    public $email;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['username', 'password', 'auth_key'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusList::class, 'targetAttribute' => ['status_id' => 'id']],
            [['redirect_url_id'], 'exist', 'skipOnError' => true, 'targetClass' => RedirectUrlList::class, 'targetAttribute' => ['redirect_url_id' => 'id']],
            [['hr_employee_id', 'username'], 'required'],
            [['password', 'password_repeat'], 'required', 'on' => self::SCENARIO_CREATE],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
            [['roles'], 'safe'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        if ($this->isNewRecord)
            $this->status_id = BaseModel::STATUS_ACTIVE;

        if ($this->isNewRecord && !empty($this->password))
            $this->setPassword();
        else
            unset($this->password);
        
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hr_employee_id' => Yii::t('app', 'Hr Employee'),
            'hr_deparment_name' => Yii::t('app', 'Hr Department'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'redirect_url_id' => Yii::t('app', 'Redirect Url'),
            'password_repeat' => Yii::t('app', 'Password Repeat'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(StatusList::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRedirectUrl()
    {
        return $this->hasOne(RedirectUrlList::class, ['id' => 'redirect_url_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployeeRelUsers::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($access_token, $type = null)
    {

        return self::findOne(['access_token' => $access_token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function setPassword()
    {
        $this->password = md5($this->password);
    }

    /**
     * @return array
     */
    public function saveUser(): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try{
            if (!$this->hr_employee_id)
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Hr employee id required')
                ];

            if ($this->isUpdate && $response['status']){
                HrEmployeeRelUsers::deleteAll(['user_id' => $this->id]);
                AuthAssignment::deleteAll(['user_id' => $this->id]);
            }

            if ($response['status']){
                $existsHrEmployeeUser = HrEmployeeRelUsers::checkExistsHrEmployeeUser($this->hr_employee_id);
                if ($existsHrEmployeeUser)
                    $response = [
                        'status' => false,
                        'message' => Yii::t('app', 'This hr employee has user')
                    ];
            }

            if ($response['status'])
                if (!$this->save())
                    $response = [
                        'status' => false,
                        'message' => Yii::t('app', 'User not saved'),
                        'errors' => $this->getErrors()
                    ];

            if ($response['status']){
                $hrEmployeeRelUsers = new HrEmployeeRelUsers([
                    'hr_employee_id' => $this->hr_employee_id,
                    'user_id' => $this->id,
                ]);
                if (!$hrEmployeeRelUsers->save())
                    $response = [
                        'status' => false,
                        'message' => Yii::t('app', 'Hr Employee Rel User not saved'),
                        'errors' => $hrEmployeeRelUsers->getErrors()
                    ];
            }

            if ($response['status'] && !empty($this->roles)){
                foreach ($this->roles as $key => $role)
                {
                    $authAssignment = new AuthAssignment([
                        'item_name' => $key,
                        'user_id' => (string)$this->id,
                        'created_at' => time(),
                    ]);
                    if (!$authAssignment->save()){
                        $response = [
                            'status' => false,
                            'message' => Yii::t('app', 'Auth assigment not saved'),
                            'errors' => $authAssignment->getErrors()
                        ];
                        break;
                    }
                }
            }

            if($response['status'])
                $transaction->commit();
            else
                $transaction->rollBack();
            
        } catch(\Exception $e){
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }
}
