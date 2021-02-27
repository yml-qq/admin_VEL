<?php
if (!function_exists('message')) {

    /**
     * 消息数组
     * @param string $msg 提示文字
     * @param bool $success 是否成功true或false
     * @param array $data 结果数据
     * @param int $code 编码
     * @return array 返回结果
     */
    function message($msg = "操作成功", $success = true, $data = [], $code = 0)
    {
        $result = ['success' => $success, 'msg' => $msg, 'data' => $data];
        if ($success) {
            $result['code'] = 0;
        } else {
            $result['code'] = $code ? $code : -1;
        }
        return $result;
    }
}

if (!function_exists('getter')) {

    /**
     * 获取数组的下标值
     * @param array $data 数据源
     * @param string $field 字段名称
     * @param string $default 默认值
     * @return mixed|string 返回结果
     * @author 牧羊人
     * @date 2019/5/23
     */
    function getter($data, $field, $default = '')
    {
        $result = $default;
        if (isset($data[$field])) {
            $result = $data[$field];
        }
        return $result;
    }
}