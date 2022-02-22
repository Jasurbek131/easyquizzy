<?php


namespace app\api\modules\v1\models;


interface ApiPlmDocumentInterface
{
    /**
     * @param $post
     * @return array
     * $_POST[] da kelgan malumotlarni saqlaydi
     */
    public static function saveData($post): array;

    /**
     * @param $post
     * @return array
     * $_POST[] da kelgan malumotlarni o'chiradi
     */
    public static function deleteDocumentItem($post): array;

    /**
     * @param $id
     * @return []
     * Saqlangan ($id) ga tegishli hujjat malumotlarni formaga yangilash uchun qaytarib beradi
     */
    public static function getDocumentElements($id): array ;

    /**
     * @param $params
     * @return mixed
     * Index oyna uchun hujjat to'plamini qayatarib beradi
     */
    public static function getPlmDocuments($params);
}