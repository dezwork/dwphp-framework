<?php

namespace util;


class Arrays{

	/**
    * Remova as duplicatas de uma matriz.
    *
    * Esta versão é mais rápida do que o conjunto array_unique().
    *
    * Notes on time requirements:
    *   array_unique -> O(n log n)
    *   array_flip -> O(n)
    *
    * @param  $array
    * @return $array
    */
    public static function fastArrayUnique($array){
        $array = array_keys(array_flip($array));
        return $array;
   	}

    /**
    * Acessa um índice de matriz, recuperando o valor armazenado ali se existir ou um padrão se não o fizer.
    * Esta função permite acessar de forma concisa um índice que pode ou não existir sem
    *
    * @param  array  $var     Array value to access
    * @param  mixed  $default Default value to return if the key is not
    *                         present in the array
    * @return mixed
    */
    public static function arrayGet(&$var, $default = null){
        if (isset($var)) {
            return $var;
        }
        return $default;
    }


    /**
    * Returns boolean if a function is an associative array
    *
    * @param  array   $array        An array to test
    *
    * @return boolean
    */
    public static function isAssocArray($array){
        if (!is_array($array)) {
            return false;
        }
        // $array = array() is not associative
        if (sizeof($array) === 0) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }


    /**
    * Retorna booleano se uma função é uma matriz numérica plana / seqüencial
    *
    * @param  array   $array        An array to test
    *
    * @return boolean
    */
    public static function isNumericArray($array){
        if (!is_array($array)) { return false; }
        $current = 0;
        foreach (array_keys($array) as $key) {
            if ($key !== $current) { return false; }
            $current++;
        }
        return true;
    }


    /**
     * Retorna o primeiro elemento de uma matriz.
     *
     * @param  array $array
     * @return mixed
     */
    public static function arrayFirst(array $array){
        return reset($array);
    }


    /**
     * Retorna o último elemento de uma matriz
     *
     * @param  array $array
     * @return mixed
     */
    public static function arrayLast(array $array){
        return end($array);
    }

    /**
     * Retorna a primeira chave em uma matriz.
     *
     * @param  array $array
     * @return int|string
     */
    public static function arrayFirstKey(array $array){
        reset($array);
        return key($array);
    }

    /**
     * Retorna a última chave de uma matriz.
     *
     * @param  array $array
     * @return int|string
     */
    public static function arrayLastKey(array $array){
        end($array);
        return key($array);
    }

    /**
     * Aplique uma matriz multidimensional em uma matriz unidimensional.
     *     *
     * @param  array   $array         The array to flatten
     * @param  boolean $preserve_keys Whether or not to preserve array keys.
     *                                Keys from deeply nested arrays will
     *                                overwrite keys from shallowy nested arrays
     * @return array
     */
    public static function arrayFlatten(array $array, $preserve_keys = true){
        $flattened = array();
        array_walk_recursive($array, function ($value, $key) use (&$flattened, $preserve_keys) {
            if ($preserve_keys && !is_int($key)) {
                $flattened[$key] = $value;
            } else {
                $flattened[] = $value;
            }
        });
        return $flattened;
    }
    /**
     * Aceita uma matriz e retorna uma matriz de valores da matriz conforme especificado pelo $field.
     * Por exemplo, se a matriz estiver cheia de objetos e você chamar util::array_pluck ($array, 'name'), a função será
     * retornar uma matriz de valores de $array[]->name.
     *
     * @param  array   $array            An array
     * @param  string  $field            The field to get values from
     * @param  boolean $preserve_keys    Whether or not to preserve the
     *                                   array keys
     * @param  boolean $remove_nomatches If the field doesn't appear to be set,
     *                                   remove it from the array
     * @return array
     */
    public static function arrayPluck(array $array, $field, $preserve_keys = true, $remove_nomatches = true){
        $new_list = array();
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                if (isset($value->{$field})) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value->{$field};
                    } else {
                        $new_list[] = $value->{$field};
                    }
                } elseif (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            } else {
                if (isset($value[$field])) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value[$field];
                    } else {
                        $new_list[] = $value[$field];
                    }
                } elseif (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            }
        }
        return $new_list;
    }

    /**
     * Procura um determinado valor em uma matriz de arrays, objetos e valores escalares.
     * Você pode opcionalmente especificar um campo das matrizes e objetos aninhados para pesquisar.
     *
     * @param  array   $array  The array to search
     * @param  scalar  $search The value to search for
     * @param  string  $field  The field to search in, if not specified
     *                         all fields will be searched
     * @return boolean|scalar  False on failure or the array key on success
     */
    public static function arraySearchDeep(array $array, $search, $field = false){
        // *grumbles* stupid PHP type system
        $search = (string) $search;
        foreach ($array as $key => $elem) {
            // *grumbles* stupid PHP type system
            $key = (string) $key;
            if ($field) {
                if (is_object($elem) && $elem->{$field} === $search) {
                    return $key;
                } elseif (is_array($elem) && $elem[$field] === $search) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            } else {
                if (is_object($elem)) {
                    $elem = (array) $elem;
                    if (in_array($search, $elem)) {
                        return $key;
                    }
                } elseif (is_array($elem) && in_array($search, $elem)) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            }
        }
        return false;
    }


    /**
     * Retorna uma matriz contendo todos os elementos do arr1 depois de aplicar a função de retorno de chamada a cada um.
     *
     * @param  string  $callback     Callback function to run for each
     *                               element in each array
     * @param  array   $array        An array to run through the callback
     *                               function
     * @param  boolean $on_nonscalar Whether or not to call the callback
     *                               function on nonscalar values
     *                               (Objects, resources, etc)
     * @return array
     */
    public static function arrayMapDeep(array $array, $callback, $on_nonscalar = false){
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $args = array($value, $callback, $on_nonscalar);
                $array[$key] = call_user_func_array(array(__CLASS__, __FUNCTION__), $args);
            } elseif (is_scalar($value) || $on_nonscalar) {
                $array[$key] = call_user_func($callback, $value);
            }
        }
        return $array;
    }


    /**
     * Combina dois arrays de forma recursiva e retorna o resultado.
     *
     * @param   array   $dest               Destination array
     * @param   array   $src                Source array
     * @param   boolean $appendIntegerKeys  Se deseja anexar elementos de $src a $ est se a chave for um número inteiro. Este é o comportamento padrão. Caso contrário, elementos de $src substituirão os em $dest.

     * @return  array
     */
    public static function arrayMergeDeep(array $dest, array $src, $appendIntegerKeys = true){
        foreach ($src as $key => $value) {
            if (is_int($key) and $appendIntegerKeys) {
                $dest[] = $value;
            } elseif (isset($dest[$key]) and is_array($dest[$key]) and is_array($value)) {
                $dest[$key] = static::arrayMergeDeep($dest[$key], $value, $appendIntegerKeys);
            } else {
                $dest[$key] = $value;
            }
        }
        return $dest;
    }

    public static function arrayClean(array $array){
        return array_filter($array);
    }

}