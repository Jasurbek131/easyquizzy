<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr-organisations}}`.
 */
class m220128_114544_add_some_tree_column_to_hr_organisations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    const TABLE_NAME = '{{%hr-organisations}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'root', $this->integer());
        $this->addColumn(self::TABLE_NAME, 'lft', $this->integer()/*->notNull()*/); // TODO notNull larni o'zgartiramiz ish tugaganda
        $this->addColumn(self::TABLE_NAME, 'rgt', $this->integer()/*->notNull()*/);
        $this->addColumn(self::TABLE_NAME, 'lvl', $this->smallInteger(5)/*->notNull()*/);
        $this->addColumn(self::TABLE_NAME, 'icon', $this->string(255));
        $this->addColumn(self::TABLE_NAME, 'icon_type', $this->smallInteger(1)/*->notNull()*/ ->defaultValue(1));
        $this->addColumn(self::TABLE_NAME, 'active', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'selected', $this->boolean()/*->notNull()*/ ->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'disabled', $this->boolean()/*->notNull()*/ ->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'readonly', $this->boolean()/*->notNull()*/ ->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'visible', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'collapsed', $this->boolean()/*->notNull()*/ ->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'movable_u', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'movable_d', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'movable_l', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'movable_r', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'removable', $this->boolean()/*->notNull()*/ ->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'removable_all', $this->boolean()/*->notNull()*/ ->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'child_allowed', $this->boolean()/*->notNull()*/ ->defaultValue(true));

        $this->createIndex('tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('tree_NK2', self::TABLE_NAME, 'lft');
        $this->createIndex('tree_NK3', self::TABLE_NAME, 'rgt');
        $this->createIndex('tree_NK4', self::TABLE_NAME, 'lvl');
        $this->createIndex('tree_NK5', self::TABLE_NAME, 'active');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('tree_NK1', self::TABLE_NAME);
        $this->dropIndex('tree_NK2', self::TABLE_NAME);
        $this->dropIndex('tree_NK3', self::TABLE_NAME);
        $this->dropIndex('tree_NK4', self::TABLE_NAME);
        $this->dropIndex('tree_NK5', self::TABLE_NAME);

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
    }
}
