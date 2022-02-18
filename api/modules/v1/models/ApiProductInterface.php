<?php
namespace app\api\modules\v1\models;

interface ApiProductInterface
{
    /**
     * @param $post
     * @return array
     * Agar kerakli mahsulot selectda topilmasa modal oyna orqali yangi mahsulot yaratadi
     */
    public static function saveApiProduct($post):array;

    /**
     * @param $post
     * @return array
     * Mahsulotlardan mahsulotlar to'plamini yaratadi.
     * Uskunalar guruhini yaratadi
     * Lifecycle uchun life time belgilab beradi
     */
    public static function saveApiProductEquipment($post):array;

    /**
     * @param $id
     * @return array
     * Saqlangan ($id) tegishli malumotlarni yangilash uchun formaga qaytarib beradi
     */
    public static function getProductFormData($id):array;

    /**
     * @param $post
     * @return array
     * Malumotlar ochirish uchun
     */
    public static function deleteApiProductEquipment($post):array;
}