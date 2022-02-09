<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%some_tree_column_to_hr_departments}}`.
 */
class m220201_104202_create_some_tree_column_to_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    const TABLE_NAME = '{{%hr_departments}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(self::TABLE_NAME,'root', $this->integer());
        $this->addColumn(self::TABLE_NAME,'lft', $this->integer());
        $this->addColumn(self::TABLE_NAME,'rgt', $this->integer());
        $this->addColumn(self::TABLE_NAME,'lvl', $this->smallInteger(5));
        $this->addColumn(self::TABLE_NAME,'icon', $this->string(255));
        $this->addColumn(self::TABLE_NAME,'icon_type', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn(self::TABLE_NAME,'active', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'selected', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'disabled', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'readonly', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'visible', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'collapsed', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME,'movable_u', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_d', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_l', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'movable_r', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'removable', $this->boolean()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME,'removable_all', $this->boolean()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'child_allowed', $this->boolean()->defaultValue(true));

        $this->createIndex('hr_departments_tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('hr_departments_tree_NK2', self::TABLE_NAME, 'lft');
        $this->createIndex('hr_departments_tree_NK3', self::TABLE_NAME, 'rgt');
        $this->createIndex('hr_departments_tree_NK4', self::TABLE_NAME, 'lvl');
        $this->createIndex('hr_departments_tree_NK5', self::TABLE_NAME, 'active');

        $this->renameColumn(self::TABLE_NAME,'name_uz','name');

        // drops foreign key for table `{{%hr_organisations}}`
        $this->dropForeignKey(
            '{{%fk-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}'
        );

        // drops index for column `hr_organisation_id`
        $this->dropIndex(
            '{{%idx-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}'
        );
        $this->dropForeignKey(
            '{{%fk-users-hr_organisation_id}}',
            '{{%users}}'
        );

        // drops index for column `hr_organisation_id`
        $this->dropIndex(
            '{{%idx-users-hr_organisation_id}}',
            '{{%users}}'
        );

        $this->addColumn('{{%users}}', 'hr_department_id', $this->integer());

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-users-hr_department_id}}',
            '{{%users}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_department_id}}`
        $this->addForeignKey(
            '{{%fk-users-hr_department_id}}',
            '{{%users}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('hr_departments_tree_NK1', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK2', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK3', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK4', self::TABLE_NAME);
        $this->dropIndex('hr_departments_tree_NK5', self::TABLE_NAME);

        $this->dropColumn(self::TABLE_NAME, 'child_allowed');
        $this->dropColumn(self::TABLE_NAME,'root');
        $this->dropColumn(self::TABLE_NAME,'lft');
        $this->dropColumn(self::TABLE_NAME,'rgt');
        $this->dropColumn(self::TABLE_NAME,'lvl');
        $this->dropColumn(self::TABLE_NAME,'icon');
        $this->dropColumn(self::TABLE_NAME,'icon_type');
        $this->dropColumn(self::TABLE_NAME,'active');
        $this->dropColumn(self::TABLE_NAME,'selected');
        $this->dropColumn(self::TABLE_NAME,'disabled');
        $this->dropColumn(self::TABLE_NAME,'readonly');
        $this->dropColumn(self::TABLE_NAME,'visible');
        $this->dropColumn(self::TABLE_NAME,'collapsed');
        $this->dropColumn(self::TABLE_NAME,'movable_u');
        $this->dropColumn(self::TABLE_NAME,'movable_d');
        $this->dropColumn(self::TABLE_NAME,'movable_l');
        $this->dropColumn(self::TABLE_NAME,'movable_r');
        $this->dropColumn(self::TABLE_NAME,'removable');
        $this->dropColumn(self::TABLE_NAME,'removable_all');

        $this->renameColumn(self::TABLE_NAME,'name','name_uz');

        // creates index for column `hr_organisation_id`
        $this->createIndex(
            '{{%idx-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}',
            'hr_organisation_id'
        );

        // add foreign key for table `{{%hr_organisations}}`
        $this->addForeignKey(
            '{{%fk-hr_departments-hr_organisation_id}}',
            '{{%hr_departments}}',
            'hr_organisation_id',
            '{{%hr_organisations}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `hr_organisation_id`
        $this->createIndex(
            '{{%idx-users-hr_organisation_id}}',
            '{{%users}}',
            'hr_organisation_id'
        );

        // add foreign key for table `{{%hr_organisation_id}}`
        $this->addForeignKey(
            '{{%fk-users-hr_organisation_id}}',
            '{{%users}}',
            'hr_organisation_id',
            '{{%hr_organisations}}',
            'id',
            'RESTRICT'
        );

        // drops foreign key for table `{{%hr_department_id}}`
        $this->dropForeignKey(
            '{{%fk-users-hr_department_id}}',
            '{{%users}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-users-hr_department_id}}',
            '{{%users}}'
        );

        $this->dropColumn('{{%users}}', 'hr_department_id');
    }
}
