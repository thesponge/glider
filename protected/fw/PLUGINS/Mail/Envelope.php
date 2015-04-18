<?php
/**
 * Process the contact form's variables and prepares them for delivery
 * (or return them to sender)
 *
 *
 * @author Victor Nițu <victor@debian.org.ro>
 * @desc Process the contact form's variables and prepares them for delivery
 * (or return them to sender)
 *
 */
class Envelope
{
    var $items;             // $_REQUEST items to be validated  and sent
    var $status = FALSE;    // Whether is a TRUE envelope or is a fake (FALSE)
    var $checked = array(); // Array containing checked items
    var $validItems = 0;
    var $invalidItems = 0;
    var $errors = array();
    var $v;

    public function __construct($items)
    {/*{{{*/
        $this->items = (is_array($items) && count($items)>0 ? $items : NULL);
        foreach ($this->items as $key => $value) {
            $this->items[$key] = array(
                'args' => $value[0],
                'desc' => $value[1],
                'content' => $_POST[$key]
            );
        }

        $this->v = new Validation();

        $this->parseArgs($this->items);

        $this->mark();
    }/*}}}*/

    protected function parseArgs($items)
    {/*{{{*/
        foreach ($items as $key => $item) {
            $args = explode(',', $item['args']);
            foreach ($args as $k => &$v) {
                $v = trim($v);
            }

            $rule = $args[0];
            unset($args[0]);
            $n=count($args);
            //var_dump($args);
            switch ($n)
            {/*{{{ the actual switch */
                case 0:
                    $this->checked[$key] = $this->v->$rule(
                        $this->items[$key]['content']
                    );
                    break;
                case 1:
                    $this->checked[$key] = $this->v->$rule(
                        $this->items[$key]['content'],$args[1]
                    );
                    break;
                case 2:
                default:
                    $this->checked[$key] = $this->v->$rule(
                        $this->items[$key]['content'],$args[1],$args[2]
                    );
                    break;
            }/*}}}*/
        }
    }/*}}}*/

    # deprecated
    protected function check($items)
    {/*{{{*/
        if($this->items === NULL) return 0;
        else
            foreach ($items as $key => $item) {
                switch($key) {
                    case 'email':
                        $this->checked[$key] = parent::email(
                            $this->items[$key]['content']
                        );
                        break;
                    case 'name':
                        if ( parent::name(
                            $this->items[$key]['content'], 40, 'max'
                        ) === 'valid') {

                            $this->checked[$key] = parent::name(
                                $this->items[$key]['content'], 3, 'min'
                            );
                        }
                        break;
                    case 'built':
                    case 'description':
                        $this->checked[$key] = parent::text(
                            $this->items[$key]['content'], 3, 'min'
                        );
                        break;
                    case 'role':
                    case 'title':
                        $this->checked[$key] = 'valid';
                        break;
                    case 'accept':
                        $this->checked[$key] = parent::text(
                            $this->items[$key]['content'], 1, 'min'
                        );
                        break;
                    default:
                        break;
                }
            }
    }/*}}}*/

    protected function mark()
    {/*{{{*/
        $this->countValid($this->items);
        $this->status = ($this->validItems == count($this->items)
            ? TRUE : FALSE );
    }/*}}}*/

    protected function countValid($items)
    {/*{{{*/
        foreach ($items as $key => $item) {
            if ($this->checked[$key]==1)
                $this->validItems++;
            else {
                $this->invalidItems++;
                array_push($this->errors, $item['desc']);
            }
        }

    }/*}}}*/

}
