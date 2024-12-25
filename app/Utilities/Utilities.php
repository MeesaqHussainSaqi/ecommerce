<?php

namespace App\Utilities;

use Illuminate\Http\Request;
use App\Configurations\CleanJsonSerializer;
use App\Models\Dealer;
use App\Models\User;
use App\Response\ValidationResponse;
use App\Response\ValidationReponseDetail;
use App\Response\SuccessResponse;
use App\Response\BaseResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Utilities
{
    // Use this func instead of DefaultAddAttributes, DefaultUpdateAttributes & DefaultDeleteAttributes
    /**
     * Function to add default attributes to request
     */
    public static function AddIsEnabledAttributeToRequest(Request $request, $type)
    {
        $request->request->add(['is_enable' => $type]);
    }

    /**
     * Remove keys except provided, first convert values to keys b/c keys are in values and indexes in keys
     *
     * @param Request $request
     * @param array $keys
     *
     */
    public static function RemoveAttributesExcept(Request $request, array $keys)
    {
        //  Set indexes as values and keys at key
        $onlyKeys = array();
        foreach ($keys as $key => $value) {
            $onlyKeys[$value] = $key;
        }

        //  Remove keys except provided.
        $data = $request->all();
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $onlyKeys)) {
                $request->request->remove($key);
            }
        }
    }

    /**
     * This static fucntion can be called to generate failed json response.
     *
     * @param number $code
     * @param string $message
     * @param array $errors
     * @return string
     */
    public static function BuildFailedValidationResponse($status, $code, $message, array $errors)
    {
        $response = new ValidationResponse();
        $response->setStatus($status);
        $response->setCode($code);
        $response->setMessage($message);

        $vdList = array();

        foreach ($errors as $key => $values) {

            $vd = new ValidationReponseDetail();
            $vd->setType("validation_error");
            $vd->setField_name($key);
            $vd->setDetail($values);
            $vdList[] = $vd;
        }

        $response->setErrors($vdList);

        $cleanJsonSerializer = new CleanJsonSerializer();
        return $cleanJsonSerializer->Serialize($response);
    }
    /**
     * This static fucntion can be called to generate base json response.
     *
     * @param number $code
     * @param string $message
     * @return string
     */
    public static function BuildBaseResponse($status, $code, $message)
    {
        $response = new BaseResponse();
        $response->setStatus($status);
        $response->setCode($code);
        $response->setMessage($message);
        $cleanJsonSerializer = new CleanJsonSerializer();
        return $cleanJsonSerializer->Serialize($response);
    }

    /**
     * This static fucntion can be called to generate success json response.
     *
     * @param string $status
     * @param integer $code
     * @param string $message
     * @param $data
     * @return string
     */
    public static function BuildSuccessResponse($status, $code, $message, $data)
    {
        $response = new SuccessResponse();
        $response->setStatus($status);
        $response->setCode($code);
        $response->setMessage($message);
        $response->setResults($data);

        $cleanJsonSerializer = new CleanJsonSerializer();
        return $cleanJsonSerializer->Serialize($response);
    }

    public static function BuildBadResponse($status, $code, $message, $data)
    {
        $response = new SuccessResponse();
        $response->setStatus($status);
        $response->setCode($code);
        $response->setMessage($message);
        $response->setResults($data);

        $cleanJsonSerializer = new CleanJsonSerializer();
        return $cleanJsonSerializer->Serialize($response);
    }



    /**
     * Set column Name with Actual name.
     *
     * @param Request $request
     */
    public static function FilterColumnsModel(Request $request, $columnList, $method)
    {
        if ($method == 'GET') {

            if ($request['fields']) {
                $data = explode(",", ($request['fields']));

                foreach ($data as $key => $value) {
                    if (!isset($columnList[$value])) {
                        unset($data[$key]);
                    } else {
                        $data[$key] = $columnList[$value] . ' as ' . $value;
                    }
                }

                $request->merge(['fields' => $data]);
            }
        }

        if ($method == 'POST' || $method == 'PUT') {
            $data = $request->all();

            foreach ($data as $key => $value) {
                if (array_key_exists($key, $columnList)) {
                    $request->merge([
                        $columnList[$key] => $value,
                    ]);
                } else {
                    $request->request->remove($key);
                }
            }
        }
    }

    /**
     * The formatString function in PHP takes an input string, replaces spaces with underscores,
     * converts it to lowercase, and returns the formatted string.
     *
     * @param string inputString The input string that you want to format.
     *
     * @return string The formatted string with spaces replaced by underscores and converted to lowercase.
     */
    public static function formatString($inputString)
    {
        // Replace spaces with underscores and convert to lowercase
        $formattedString = strtolower(str_replace(' ', '_', $inputString));

        return $formattedString;
    }

    /**
     * Check if the menu name contains "Create" or "All" and return specific strings based on the condition.
     *
     * @param string $menu The original menu name
     * @return string The modified or original menu name
     */
    public static function RenameMenu($menu)
    {
        // Check if the menu name contains "All" without any other alphabets
        if (strcasecmp($menu, "All") === 0) {
            return "All";
        } elseif (stripos($menu, "Create") !== false) {
            return "Create";
        } elseif (stripos($menu, "Import") !== false) {
            return "Import";
        } elseif (stripos($menu, "assign") !== false) {
            return "Assign";
        } else {
            // If none of the specific cases are found, return the original menu name
            return $menu;
        }
    }


    /**
     * The function checks if a user has certain permissions and returns an array of formatted
     * permissions.
     *
     * @param \App\Models\User user The "user" parameter is an object representing a user. It is expected to have a
     * method called "hasPermissionTo" which takes a permission value as an argument and returns a
     * boolean indicating whether the user has that permission or not.
     * @param array permissions An array of permissions that need to be checked.
     *
     * @return array an array of permissions that the user has.
     */
    public static function checkPermissions($user, $permissions)
    {
        $returnPermissions = [];
        foreach ($permissions as $value) {
            $hasPermission = $user->hasPermissionTo($value);
            if ($hasPermission || $user->hasRole('superadmin')) {
                // $returnPermissions[] = $value;
                $returnPermissions[] = self::reFormatString($value);
            }
        }

        return $returnPermissions;
    }

    /**
     * The function reFormatString takes an input string, splits it by underscores, and returns the
     * last word in lowercase.
     *
     * @param string inputString The inputString parameter is a string that contains words separated by
     * underscores.
     *
     * @return string the last word of the input string, converted to lowercase.
     */
    public static function reFormatString($inputString)
    {
        // Split the string by underscores and get the last element
        $stringParts = explode('_', $inputString);
        $lastWord = end($stringParts);

        return $lastWord;
    }

    public static function removeSpacesAndCapitalize($inputString)
    {
        $newString = Str::replace('_', ' ', $inputString);
        return Str::title($newString);
    }

    /**
     * GetDealerID function description
     *
     * @param Request $request description
     * @return int
     */
    public static function GetDealerID(Request $request)
    {
        $id = null;
        $user = $request->user();

        if ($user->hasRole('superadmin')) {
            $id = 1; //1023 is dealer code and not the id
        } else {
            $id = $user->UserDealerMapping->dealer_id;
        }
        return $id;
    }

    /**
     * GetDealerCode function description
     *
     * @param Request $request description
     * @return int
     */
    public static function GetDealerCode(Request $request)
    {
        $code = null;
        $user = $request->user();

        if ($user->hasRole('superadmin')) {
            $code = 1023;
        } else if ($user) {
            $dealerID = $user->UserDealerMapping->dealer_id;
            $dealer = Dealer::find($dealerID);
            $code = (int) $dealer->dealer_code;
        }

        return $code;
    }

    public static function GetDealerCodeById($id)
    {
        $dealer = Dealer::find($id);
        if ($dealer) {
            return $dealer['dealer_code'];
        }
        return null;
    }

    public static function GetDealerCodeByUserId($id)
    {
        $user = User::find($id);
        if ($user) {
            $dealerID = $user->UserDealerMapping->dealer_id;
            $dealer = Dealer::find($dealerID);
            $code = (int) $dealer->dealer_code;
            return $code;
        }
        return null;
    }

    public static function GetDealerIDByCode($code)
    {
        $dealer = Dealer::where('dealer_code', $code)->first();
        if ($dealer) {
            return $dealer['id'];
        }
        return null;
    }

    public static function numberTowords($num = '')
    {

        $num = (string) ((int) $num);

        if ((int) ($num) && ctype_digit($num)) {
            $words  = array();

            $num    = str_replace(array(',', ' '), '', trim($num));

            $list1  = array(
                '',
                'One',
                'Two',
                'Three',
                'Four',
                'Five',
                'Six',
                'Seven',
                'Eight',
                'Nine',
                'Ten',
                'Eleven',
                'Twelve',
                'Thirteen',
                'Fourteen',
                'Fifteen',
                'Sixteen',
                'Seventeen',
                'Eighteen',
                'Nineteen'
            );

            $list2  = array(
                '',
                'ten',
                'twenty',
                'thirty',
                'forty',
                'fifty',
                'sixty',
                'seventy',
                'eighty',
                'ninety',
                'hundred'
            );

            $list3  = array(
                '',
                'thousand',
                'million',
                'billion',
                'trillion',
                'quadrillion',
                'quintillion',
                'sextillion',
                'septillion',
                'octillion',
                'nonillion',
                'decillion',
                'undecillion',
                'duodecillion',
                'tredecillion',
                'quattuordecillion',
                'quindecillion',
                'sexdecillion',
                'septendecillion',
                'octodecillion',
                'novemdecillion',
                'vigintillion'
            );

            $num_length = strlen($num);
            $levels = (int) (($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num    = substr('00' . $num, -$max_length);
            $num_levels = str_split($num, 3);

            foreach ($num_levels as $num_part) {
                $levels--;
                $hundreds   = (int) ($num_part / 100);
                $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
                $tens       = (int) ($num_part % 100);
                $singles    = '';

                if ($tens < 20) {
                    $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                } else {
                    $tens = (int) ($tens / 10);
                    $tens = ' ' . $list2[$tens] . ' ';
                    $singles = (int) ($num_part % 10);
                    $singles = ' ' . $list1[$singles] . ' ';
                }
                $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
            }
            $commas = count($words);
            if ($commas > 1) {
                $commas = $commas - 1;
            }

            $words  = implode(', ', $words);

            //Replacing multiples of spaces with one space
            $words  = trim(str_replace(' ,', ',', Utilities::trim_all(ucwords($words))), ', ');


            return $words;
        } else if (!((int) $num)) {
            return 'Zero';
        }
        return '';
    }

    public static function trim_all($str, $what = NULL, $with = ' ')
    {
        if ($what === NULL) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim(preg_replace("/[" . $what . "]+/", preg_quote($with), preg_quote($str)), $what);
    }
}
