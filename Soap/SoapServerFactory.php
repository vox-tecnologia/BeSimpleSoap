<?php
/*
 * This file is part of the WebServiceBundle.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bundle\WebServiceBundle\Soap;

/**
 *
 * @author Christian Kerl <christian-kerl@web.de>
 */
use Bundle\WebServiceBundle\ServiceDefinition\Type;

use Bundle\WebServiceBundle\ServiceDefinition\Dumper\FileDumper;

use Bundle\WebServiceBundle\Converter\ConverterRepository;

use Bundle\WebServiceBundle\SoapKernel;

use Bundle\WebServiceBundle\Util\QName;

use Bundle\WebServiceBundle\ServiceDefinition\ServiceDefinition;

class SoapServerFactory
{
    private $definition;
    private $converters;
    private $dumper;

    public function __construct(ServiceDefinition $definition, ConverterRepository $converters, FileDumper $dumper)
    {
        $this->definition = $definition;
        $this->converters = $converters;
        $this->dumper = $dumper;
    }

    public function create(&$request, &$response)
    {
        $file = $this->dumper->dumpServiceDefinition($this->definition);

        $server = new \SoapServer(
            $file,
            array(
                'classmap' => $this->createSoapServerClassmap(),
            	'typemap'  => $this->createSoapServerTypemap($request, $response),
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            )
        );

        return $server;
    }

    private function createSoapServerTypemap(&$request, &$response)
    {
        $result = array();

        foreach($this->converters->getTypeConverters() as $typeConverter)
        {
            $result[] = array(
                'type_name' => $typeConverter->getTypeName(),
                'type_ns' => $typeConverter->getTypeNamespace(),
                'from_xml' => function($input) use (&$request, $typeConverter) {
                    return $typeConverter->convertXmlToPhp($request, $input);
                },
                'to_xml' => function($input) use (&$response, $typeConverter) {
                    return $typeConverter->convertPhpToXml($response, $input);
                }
            );
        }

        return $result;
    }

    private function createSoapServerClassmap()
    {
        $result = array();

        foreach($this->definition->getHeaders() as $header)
        {
            $this->addSoapServerClassmapEntry($result, $header->getType());
        }

        foreach($this->definition->getMethods() as $method)
        {
            foreach($method->getArguments() as $arg)
            {
                $this->addSoapServerClassmapEntry($result, $arg->getType());
            }
        }

        return $result;
    }

    private function addSoapServerClassmapEntry(&$classmap, Type $type)
    {
        $xmlType = QName::fromPackedQName($type->getXmlType())->getName();
        $phpType = $type->getPhpType();

        if(isset($classmap[$xmlType]) && $classmap[$xmlType] != $phpType)
        {
            // log warning
        }

        $classmap[$xmlType] = $phpType;
    }
}
