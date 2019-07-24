<?php

namespace Swis\JsonApi\Client\Parsers;

use Swis\JsonApi\Client\Error;
use Swis\JsonApi\Client\ErrorSource;
use Swis\JsonApi\Client\Exceptions\ValidationException;

/**
 * @internal
 */
class ErrorParser
{
    /**
     * @var \Swis\JsonApi\Client\Parsers\LinksParser
     */
    private $linksParser;

    /**
     * @var \Swis\JsonApi\Client\Parsers\MetaParser
     */
    private $metaParser;

    /**
     * @param \Swis\JsonApi\Client\Parsers\LinksParser $linksParser
     * @param \Swis\JsonApi\Client\Parsers\MetaParser  $metaParser
     */
    public function __construct(LinksParser $linksParser, MetaParser $metaParser)
    {
        $this->linksParser = $linksParser;
        $this->metaParser = $metaParser;
    }

    /**
     * @param mixed $data
     *
     * @return \Swis\JsonApi\Client\Error
     */
    public function parse($data): Error
    {
        if (!is_object($data)) {
            throw new ValidationException(sprintf('Error has to be an object, "%s" given.', gettype($data)));
        }
        if (property_exists($data, 'id') && !is_string($data->id)) {
            throw new ValidationException(sprintf('Error property "id" has to be a string, "%s" given.', gettype($data->id)));
        }
        if (property_exists($data, 'status') && !is_string($data->status)) {
            throw new ValidationException(sprintf('Error property "status" has to be a string, "%s" given.', gettype($data->status)));
        }
        if (property_exists($data, 'code') && !is_string($data->code)) {
            throw new ValidationException(sprintf('Error property "code" has to be a string, "%s" given.', gettype($data->code)));
        }
        if (property_exists($data, 'title') && !is_string($data->title)) {
            throw new ValidationException(sprintf('Error property "title" has to be a string, "%s" given.', gettype($data->title)));
        }
        if (property_exists($data, 'detail') && !is_string($data->detail)) {
            throw new ValidationException(sprintf('Error property "detail" has to be a string, "%s" given.', gettype($data->detail)));
        }

        return new Error(
            property_exists($data, 'id') ? $data->id : null,
            property_exists($data, 'links') ? $this->linksParser->parse($data->links, LinksParser::SOURCE_ERROR) : null,
            property_exists($data, 'status') ? $data->status : null,
            property_exists($data, 'code') ? $data->code : null,
            property_exists($data, 'title') ? $data->title : null,
            property_exists($data, 'detail') ? $data->detail : null,
            property_exists($data, 'source') ? $this->buildErrorSource($data->source) : null,
            property_exists($data, 'meta') ? $this->metaParser->parse($data->meta) : null
        );
    }

    /**
     * @param mixed $data
     *
     * @return \Swis\JsonApi\Client\ErrorSource
     */
    private function buildErrorSource($data): ErrorSource
    {
        if (!is_object($data)) {
            throw new ValidationException(sprintf('ErrorSource has to be an object, "%s" given.', gettype($data)));
        }
        if (property_exists($data, 'pointer') && !is_string($data->pointer)) {
            throw new ValidationException(sprintf('ErrorSource property "pointer" has to be a string, "%s" given.', gettype($data->pointer)));
        }
        if (property_exists($data, 'parameter') && !is_string($data->parameter)) {
            throw new ValidationException(sprintf('ErrorSource property "parameter" has to be a string, "%s" given.', gettype($data->parameter)));
        }

        return new ErrorSource(
            property_exists($data, 'pointer') ? $data->pointer : null,
            property_exists($data, 'parameter') ? $data->parameter : null
        );
    }
}
