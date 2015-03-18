<?php
/**
 * Response类继承phalcon
 */
namespace Engine;
class Response extends \Phalcon\Http\Response
{
    public function outData($data = array(), $code = 200, $runtime = 0, $trace = '')
    {
        if ($code == 200) {
            $rs = array(
                'code' => $code,
                'data' => $data,
                'runtime' => $runtime
            );
        } else {
            empty($data) && $code = 201;
            $rs = array(
                'code' => $code,
                'data' => $data,
                'trace' => $trace,
                'runtime' => $runtime
            );
        }
        $this->setJsonContent($rs);
        $this->setContentType('application/json; charset=utf8');
        $this->sendHeaders();
        echo $this->getContent();
    }
}