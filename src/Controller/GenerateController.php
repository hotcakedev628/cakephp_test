<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Http\ServerRequest;
    class GenerateController extends AppController
    {
        public function index()
        {
            $this->render('/Pages/Generate/auto-generate-paragraph');
        }

        public function generate() {
            $Plaintiffs = $this->_remove_space_from_array(
                explode(';', $this->request->getData('Plaintiffs'))
            );
            $Defendants = $this->_remove_space_from_array(
                explode(';', $this->request->getData('Defendants'))
            );
            $DNCR = $this->request->getData('DNCR');
            $IDNCL = $this->request->getData('IDNCL');
            $TIAA = $this->request->getData('TIAA');

            /***********************
             *   generate context  *
             ***********************/
            $plaintiffs = $this->_generate_plaintiffs($Plaintiffs);
            $brings = $this->_generate_plaintiff_brings($Plaintiffs);
            $owner = $this->_generate_plaintiff_owner($Plaintiffs);

            $defendants = $this->_generate_defendants($Defendants);
            $plaintiff_point = $this->_generate_plaintiff_point($Plaintiffs);
            $plaintiff_number = $this->_generate_plaintiff_number($Plaintiffs);
            $defendant_owner = $this->_generate_defendant_owner($Defendants);

            /*********************
             *   Merge Context   *
             *********************/
            if (count($Plaintiffs) !== 0 && count($Defendants) !== 0) {
                $first = "
                    <mark class='yellow'>$plaintiffs</mark> <mark class='yellow'>$brings</mark> this action seeking to enforce <mark class='yellow'>$owner</mark> right to privacy under the consumer-privacy provisions of the Telephone Consumer Protection Act (“TCPA”), 47 U.S.C. § 227.
                ";
                $second = "
                    <mark class='red'>$defendants</mark> violated the TCPA by using an automated dialing system, or “ATDS”, to send telemarketing text messages to <mark class='yellow'>$plaintiff_point</mark> cellular telephone <mark class='yellow'>$plaintiff_number</mark> for the purposes of advertising <mark class='red'>$defendant_owner</mark> goods and services.
                ";
                $violations = $this->_generate_violations($DNCR, $IDNCL, $TIAA, $Plaintiffs, $Defendants);
            } else {
                $first = '';
                $second = '';
                $violations = '';
                echo 'Insert Variables';
            }
            $generated = $first . $second . $violations;

            $this->set('generated', $generated);
            $this->render('/Pages/Generate/auto-generate-paragraph');
        }

        private function _remove_space_from_array($arr) {
            $targetArr = [];
            foreach($arr as $tmp) {
                if (trim($tmp) !== "") {
                    array_push($targetArr, $tmp);
                }
            }
            return $targetArr;
        }

        private function _generate_plaintiffs($Plaintiffs) {
            $len = count($Plaintiffs);
            if ($len === 1) {
                return $Plaintiffs[0] . ' ("Plaintiff")';
            } else if ($len === 2) {
                return $Plaintiffs[0] . " and " . $Plaintiffs[1] . ' ("Plaintiffs")';
            } else if ($len === 3) {
                return $Plaintiffs[0] . ", " . $Plaintiffs[1] . ", and " . $Plaintiffs[2]. ' ("Plaintiffs")';
            } else if ($len > 3 && $len <= 5) {
                return $Plaintiffs[0] . ", " . $Plaintiffs[1] . ", ..., and " . $Plaintiffs[$len - 1] . ' ("Plaintiffs")';
            } else if ($len > 5) {
                return "Plaintiffs";
            }

            return "";
        }

        private function _generate_plaintiff_owner($Plaintiffs) {
            $len = count($Plaintiffs);
            if ($len === 1) {
                return "Plaintiff's";
            } else if ($len > 1) {
                return "their";
            }
            return "Plaintiff's";
        }

        private function _generate_plaintiff_plural($Plaintiffs) {
            $len = count($Plaintiffs);
            if ($len === 1) {
                return "Plaintiff";
            } else if ($len > 1) {
                return "Plaintiffs'";
            }
            return "Plaintiff";
        }

        private function _generate_plaintiff_point($Plaintiffs) {
            $len = count($Plaintiffs);
            if ($len === 1) {
                return "Plaintiff's";
            } else if ($len > 1) {
                return "Plaintiffs'";
            }
            return "its";
        }
    
        private function _generate_plaintiff_brings($Plaintiffs) {
            $len = count($Plaintiffs);
            if ($len === 1) {
                return "brings";
            } else if ($len > 1) {
                return "bring";
            }
            return "bring";
        }

        private function _generate_plaintiff_number($Plaintiffs) {
            $len = count($Plaintiffs);
            if ($len === 1) {
                return "number";
            } else if ($len > 1) {
                return "numbers'";
            }
            return "number";
        }

        private function _generate_defendants($Defendants) {
            $len = count($Defendants);
            if ($len === 1) {
                return $Defendants[0] . ' ("Defendent")';
            } else if ($len === 2) {
                return $Defendants[0] . " and " . $Defendants[1] . ' ("Defendants")';
            } else if ($len === 3) {
                return $Defendants[0] . ", " . $Defendants[1] . ", and " . $Defendants[2]. " (Defendants)";
            } else if ($len > 3 && $len <= 5) {
                return $Defendants[0] . ", " . $Defendants[1] . ", ..., and " . $Defendants[$len - 1] . " (Defendants)";
            } else if ($len > 5) {
                return "Defendants";
            }

            return "";
        }

        private function _generate_defendant_owner($Defendants) {
            $len = count($Defendants);
            if ($len === 1) {
                return "its";
            } else if ($len > 1) {
                return "their";
            }
            return "its";
        }

        private function _generate_defendant_plural($Defendants) {
            $len = count($Defendants);
            if ($len === 1) {
                return "Defendant";
            } else if ($len > 1) {
                return "Defendants'";
            }
            return "Defendant";
        }

        private function _generate_violations($DNCR, $IDNCL, $TIAA, $Defendants, $Plaintiffs) {
            $dependant_plural = $this->_generate_defendant_plural($Defendants);
            $plaintiff_plural = $this->_generate_plaintiff_plural($Plaintiffs);
            $plaintiff_owner = $this->_generate_plaintiff_owner($Plaintiffs);
            $violations = "<mark class='blue'>Further violating the TCPA, $dependant_plural sent multiple text messages to $plaintiff_plural ";
            $violations_tiaa = ", the text messages violated the Utah Truth In Advertising Act.</mark> ";
            if (intval($DNCR) === 1) {
                $violations .= "despite $plaintiff_owner presence on the National Do Not Call Registry</mark>";
                if (intval($IDNCL) === 1) {
                    $violations .= ", <mark class='green'>and without maintaining internal do not call procedures as required by law. </mark> ";
                } else {
                    $violations .= ".";
                }
            } else {
                if (intval($IDNCL) === 1) {
                    $violations .= " without maintaining internal do not call procedures as required by law. ";
                } else {
                    $violations .= ".";
                }
            }

            if (intval($TIAA) === 1 && intval($IDNCL) === 1) {
                if (intval($DNCR) === 1) {
                    $violations .= "<mark class='purple'>Lastly" . $violations_tiaa . "</mark>";
                } else {
                    $violations .= "<mark class='purple'>Also" . $violations_tiaa . "</mark>";
                }
            }

            return $violations;
        }
    }

