<?php
namespace PHPFacile\Openstreetmap\Model;

class OverpassResponse
{
    /**
     * "raw" response from an overpass query
     *
     * @var $response
     */
    protected $response;

    /**
     * Indexed array of associative array of names in the response where idx = element index, keys = language
     *
     * @var $names
     */
    protected $names = [];

    /**
     * Indexed array of official names in the response where idx = element index
     *
     * @var $officialNames
     */
    protected $officialNames = [];

    /**
     * Indexed array of array of postal codes in the response where idx = element index
     *
     * @var $postalCodes
     */
    protected $postalCodes = [];

    /**
     * Constructor
     *
     * @param string $response An Overpass response
     * @param string $format   Overpass response format
     *
     * @return OverpassResponse
     */
    public function __construct($response, $format = 'json')
    {
        $this->response = json_decode($response, true);
    }

    /**
     * Parse an element in the response so as to retrieve "alternative" names
     *
     * @param integer $elementIdx Index of the element in the response
     *
     * @return void
     */
    protected function parseNames($elementIdx = 0)
    {
        if (false === array_key_exists($elementIdx, $this->names)) {
            if (false === array_key_exists($elementIdx, $this->response['elements'])) {
                throw new \Exception('Oups...');
            }

            $this->names[$elementIdx] = [];

            foreach ($this->response['elements'][$elementIdx]['tags'] as $tag => $value) {
                if ('name:' === substr($tag, 0, 5)) {
                    $lang = substr($tag, 5);
                    $this->names[$elementIdx][$lang] = $value;

                    // Ok... let's free a little bit of memory
                    unset($this->response['elements'][$elementIdx]['tags'][$tag]);
                } else if ('name' === $tag) {
                    $this->officialName[$elementIdx] = $value;

                    // Ok... let's free a little bit of memory
                    unset($this->response['elements'][$elementIdx]['tags'][$tag]);
                }
            }
        }
    }

    /**
     * Returns the list of names for a given element of the response
     *
     * @param integer $elementIdx Index of the element in the response
     *
     * @throws Exception
     *
     * @return string[] Associative array where keys are the language code
     */
    public function getNames($elementIdx = 0)
    {
        $this->parseNames($elementIdx);
        return $this->names[$elementIdx];
    }

    /**
     * Return the official name of an element of the response
     *
     * @param integer $elementIdx Index of the element in the response
     *
     * @throws Exception
     *
     * @return string
     */
    public function getOfficialName($elementIdx = 0)
    {
        $this->parseNames($elementIdx);
        return $this->officialName[$elementIdx];
    }

    /**
     * Returns the list of postal codes for a given element of the response
     *
     * @param integer $elementIdx Index of the element in the response
     *
     * @throws Exception
     *
     * @return string[] Array of postal code
     */
    public function getPostalCodes($elementIdx = 0)
    {
        if (true === array_key_exists($elementIdx, $this->postalCodes)) {
            return $this->postalCodes[$elementIdx];
        }

        if (false === array_key_exists($elementIdx, $this->response['elements'])) {
            throw new \Exception('Oups...');
        }

        if (true === array_key_exists('addr:postcode', $this->response['elements'][$elementIdx]['tags'])) {
            $postcode = $this->response['elements'][$elementIdx]['tags']['addr:postcode'];
            $this->postalCodes[$elementIdx] = explode(';', $postcode);

            // Ok... let's free a little bit of memory
            unset($this->response['elements'][$elementIdx]['tags']['addr:postcode']);
        }

        return $this->postalCodes[$elementIdx];
    }
}
