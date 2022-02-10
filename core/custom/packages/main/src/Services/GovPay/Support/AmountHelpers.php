<?php


namespace EvolutionCMS\Main\Services\GovPay\Support;


class AmountHelpers
{

   private  function num2text_ua($num, $_1_2, $_1_19, $des, $hang, $namecurr, $nametho, $namemil, $namemrd)
    {
        $num = trim(preg_replace('~s+~s', '', $num)); // отсекаем пробелы
        if (preg_match("/, /", $num)) {
            $num = preg_replace("/, /", ".", $num);
        } // преобразует запятую
        if (is_numeric($num)) {
            $num = round($num, 2); // Округляем до сотых (копеек)
            $num = number_format($num, 2, '.', ''); // именно два знака после запятой
            $num_arr = explode(".", $num);
            $amount = $num_arr[0]; // переназначаем для удобства, $amount - сумма без копеек
            if (strlen($amount) <= 3) {
                $res = implode(" ", $this->Triada($amount, null, $_1_2, $_1_19, $des, $hang)) . $this->Currency($amount, $namecurr);
            } else {
                $amount1 = $amount;
                while (strlen($amount1) >= 3) {
                    $temp_arr[] = substr($amount1, -3); // засовываем в массив по 3
                    $amount1 = substr($amount1, 0, -3); // уменьшаем массив на 3 с конца
                }
                if ($amount1 != '') {
                    $temp_arr[] = $amount1;
                } // добавляем то, что не добавилось по 3
                $i = 0;
                foreach ($temp_arr as $temp_var) { // переводим числа в буквы по 3 в массиве
                    $i++;
                    if ($i == 3 || $i == 4) { // миллионы и миллиарды мужского рода, а больше миллирда вам все равно не заплатят
                        if ($temp_var == '000') {

                            $temp_res[] = '';
                        } else {
                            $temp_res[] = implode(" ", $this->Triada($temp_var, 1, $_1_2, $_1_19, $des, $hang)) . $this->GetNum($i, $temp_var, $nametho, $namemil, $namemrd);
                        } # if
                    } else {
                        if ($temp_var == '000') {
                            $temp_res[] = '';
                        } else {
                            $temp_res[] = implode(" ", $this->Triada($temp_var, null, $_1_2, $_1_19, $des, $hang)) . $this->GetNum($i, $temp_var, $nametho, $namemil, $namemrd);
                        } # if
                    } # else
                } # foreach
                $temp_res = array_reverse($temp_res); // разворачиваем массив
                $res = implode(" ", $temp_res) . $this->Currency($amount, $namecurr);
            }
            if (!isset($num_arr[1]) || $num_arr[1] == '') {
                $num_arr[1] = '00';
            }
            return $res . ', ' . $num_arr[1] . ' коп.';
        } # if
    }

   private function Triada($amount, $case = null, $_1_2, $_1_19, $des, $hang)
    {
        $count = strlen($amount);
        $triada1 = array();
        for ($i = 0; $i < $count; $i++) {
            $triada[] = substr($amount, $i, 1);
        }
        $triada = array_reverse($triada); // разворачиваем массив для операций
        if (isset($triada[1]) && $triada[1] == 1) { // строго для 10-19
            $triada[0] = $triada[1] . $triada[0]; // Объединяем в единицы
            $triada[1] = ''; // убиваем десятки
            $triada[0] = $_1_19[$triada[0]]; // присваиваем
        } else { // а дальше по обычной схеме
            if (isset($case) && ($triada[0] == 1 || $triada[0] == 2)) { // если требуется м.р.
                $triada[0] = $_1_2[$triada[0]]; // единицы, массив мужского рода
            } else {
                if ($triada[0] != 0) {
                    $triada[0] = $_1_19[$triada[0]];
                } else {
                    $triada[0] = '';
                } // единицы
            } # if
            if (isset($triada[1]) && $triada[1] != 0) {
                $triada[1] = $des[$triada[1]];
            } else {
                $triada[1] = '';
            } // десятки
        }
        if (isset($triada[2]) && $triada[2] != 0) {
            $triada[2] = $hang[$triada[2]];
        } else {
            $triada[2] = '';
        } // сотни
        $triada = array_reverse($triada); // разворачиваем массив для вывода
        foreach ($triada as $triada_) { // вычищаем массив от пустых значений
            if ($triada_ != '') {
                $triada1[] = $triada_;
            }
        } # foreach
        return $triada1;
    }

  private function Currency($amount, $namecurr)
    {
        /*
        $last2 = substr($amount, -2); // последние 2 цифры
        $last1 = substr($amount, -1); // последняя 1 цифра
        $last3 = substr($amount, -3); //последние 3 цифры
        if ((strlen($amount) != 1 && substr($last2, 0, 1) == 1) || $last1 >= 5 || $last3 == '000') {
            $curr = $namecurr[3];
        } // от 10 до 19
        else if ($last1 == 1) {
            $curr = $namecurr[1];
        } // для 1-цы
        else {
            $curr = $namecurr[2];
        } // все остальные 2, 3, 4
        return ' ' . $curr;
        */
        $forms = array($namecurr[1], $namecurr[2], $namecurr[3]);
        $n = $amount;
        return ' ' . ($n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]));
    }

  private  function GetNum($level, $amount, $nametho, $namemil, $namemrd)
    {
        if ($level == 1) {
            $num_arr = null;
        } else if ($level == 2) {
            $num_arr = $nametho;
        } else if ($level == 3) {
            $num_arr = $namemil;
        } else if ($level == 4) {
            $num_arr = $namemrd;
        } else {
            $num_arr = null;
        }
        if (isset($num_arr)) {
            $last2 = substr($amount, -2);
            $last1 = substr($amount, -1);
            if ((strlen($amount) != 1 && substr($last2, 0, 1) == 1) || $last1 >= 5) {
                $res_num = $num_arr[3];
            } // 10-19
            else if ($last1 == 1) {
                $res_num = $num_arr[1];
            } // для 1-цы
            else {
                $res_num = $num_arr[2];
            } // все остальные 2, 3, 4
            return ' ' . $res_num;
        } # if
    }

    public static function amountInWord($inputSum)
    {

        $_1_2[1] = "один";
        $_1_2[2] = "два";

        $_1_19[1] = "одна";
        $_1_19[2] = "дві";
        $_1_19[3] = "три";
        $_1_19[4] = "чотири";
        $_1_19[5] = "п'ять";
        $_1_19[6] = "шість";
        $_1_19[7] = "сім";
        $_1_19[8] = "вісім";
        $_1_19[9] = "дев'ять";
        $_1_19[10] = "десять";

        $_1_19[11] = "одинадцять";
        $_1_19[12] = "дванадцять";
        $_1_19[13] = "тринадцять";
        $_1_19[14] = "чотирнадцять";
        $_1_19[15] = "п'ятнадцять";
        $_1_19[16] = "шістнадцять";
        $_1_19[17] = "сімнадцять";
        $_1_19[18] = "вісімнадцять";
        $_1_19[19] = "дев'ятнадцять";


        $des[2] = "двадцять";
        $des[3] = "тридцять";
        $des[4] = "сорок";
        $des[5] = "п'ятдесят";
        $des[6] = "шістдесят";
        $des[7] = "сімдесят";
        $des[8] = "вісімдесят";
        $des[9] = "дев'яносто";

        $hang[1] = "сто";
        $hang[2] = "двісті";
        $hang[3] = "триста";
        $hang[4] = "чотириста";
        $hang[5] = "п'ятсот";
        $hang[6] = "шістсот";
        $hang[7] = "сімсот";
        $hang[8] = "вісімсот";
        $hang[9] = "дев'ятьсот";

        $namecurr[1] = "гривня"; // 1
        $namecurr[2] = "гривні"; // 2, 3, 4
        $namecurr[3] = "гривень"; // >4

        $nametho[1] = "тисяча"; // 1
        $nametho[2] = "тисячі"; // 2, 3, 4
        $nametho[3] = "тисяч"; // >4

        $namemil[1] = "мільйон"; // 1
        $namemil[2] = "мільйона"; // 2, 3, 4
        $namemil[3] = "мільйонів"; // >4

        $namemrd[1] = "мільярд"; // 1
        $namemrd[2] = "мільярда"; // 2, 3, 4
        $namemrd[3] = "мільярдів"; // >4

        $obj = new self();

        return $obj->num2text_ua($inputSum, $_1_2, $_1_19, $des, $hang, $namecurr, $nametho, $namemil, $namemrd);
    }





}
