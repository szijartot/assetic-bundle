<?php
namespace Symfony\Bundle\AsseticBundle\Factory\Resource\Normalizer;

class FormulaNormalizer
{
    const KEY_REMOVE = 'remove';
    const KEY_INPUT = 'input';
    const KEY_POSITION = 'position';

    /**
     * @param array $formula
     * @return array
     */
    public function normalize( array $formula )
    {

        list($input, $filters, $options ) = $formula;

        $removeItems = $this->collectRemoveItems($input);

        $input = array_diff(
            $this->processInputs( $input ),
            $removeItems
        );

        return array( $input, $filters, $options );
    }

    /**
     * @param array $formulae
     * @return array
     */
    public function normalizeList( array $formulae )
    {
        $result = array();

        foreach ( $formulae as $name=>$fomula){
            $result[$name] = $this->normalize($fomula);
        }

        return $result;
    }


    private function collectRemoveItems(array $inputList)
    {
        $removeItems = array();

        foreach ( $inputList as $input ){
            if( !empty($input[self::KEY_REMOVE]) ){
                $removeItems[] = $input[self::KEY_INPUT];
            }
        }

        return $removeItems;
    }

    /**
     * @param array $inputList
     * @return array
     */
    private function processInputs(array $inputList)
    {
        $map = array();
        $result = array();

        foreach ( $inputList as $input ){
            $map[ $input[self::KEY_POSITION] ][] = $input[self::KEY_INPUT];
        }

        ksort( $map );

        foreach ( $map as $items ){
            foreach ($items as $item){
                $result[] = $item;
            }
        }

        return $result;
    }
}