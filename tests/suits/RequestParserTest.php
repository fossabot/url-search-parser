<?php

namespace Tests\Mrcnpdlk\Lib\UrlSearchParser;

use http\Exception\InvalidArgumentException;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException;
use Mrcnpdlk\Lib\UrlSearchParser\RequestParser;
use PHPUnit\Framework\TestCase;

/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 23:37
 */
class RequestParserTest extends TestCase
{
    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testEmptyParamException(): void
    {
        $this->expectException(EmptyParamException::class);
        $url   = 'https://api.expample.com?sort=,';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testEmptyParamException2(): void
    {
        $this->expectException(EmptyParamException::class);
        $url   = 'https://api.expample.com?sort=-';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testFilter_appendParam(): void
    {
        $url     = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $oParser->getFilter()->appendParam(new FilterParam('isFoo', 'eq', 0));
        $this->assertCount(2, $oParser->getFilter()->getByParam('isFoo'));
        $this->assertSame($query, $oParser->getQuery());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testFilter_replaceParam(): void
    {
        $url     = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $oParser->getFilter()->replaceParam(new FilterParam('isFoo', 'eq', 0));
        $this->assertCount(1, $oParser->getFilter());

        $oParser->getFilter()->replaceParam(new FilterParam('bar', 'eq', 'bar'));
        $this->assertCount(2, $oParser->getFilter());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamException(): void
    {
        $url   = 'https://api.expample.com?filter=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionEmptyOperator(): void
    {
        $url   = 'https://api.expample.com?filter[foo][]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionEmptyParam(): void
    {
        $url   = 'https://api.expample.com?filter[][eq]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionInvalidLimit(): void
    {
        $url   = 'https://api.expample.com?limit=-1';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionInvalidOffset(): void
    {
        $url   = 'https://api.expample.com?offset=-1';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionInvalidOperator(): void
    {
        $url   = 'https://api.expample.com?filter[foo][qwer]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @expectedException \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionInvalidPage(): void
    {
        $url   = 'https://api.expample.com?page=-1';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseAddons(): void
    {
        $url     = 'https://api.expample.com?foo=bar&baz=5';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $this->assertEquals('bar', $oParser->getQueryParam('foo', 'string'));
        $this->assertEquals(5, $oParser->getQueryParam('baz', 'int'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseAddonsArrayAsArray(): void
    {
        $url     = 'https://api.expample.com?foo[]=1&foo[]=2&foo[]=3';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $this->assertEquals('1,2,3', $oParser->getQueryParam('foo', 'string'));
        $this->assertEquals(['1', '2', '3'], $oParser->getQueryParam('foo', 'array'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseAddonsArrayAsString(): void
    {
        $url     = 'https://api.expample.com?foo=1,2,3';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $this->assertEquals('1,2,3', $oParser->getQueryParam('foo', 'string'));
        $this->assertEquals(['1', '2', '3'], $oParser->getQueryParam('foo', 'array'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseAddonsAsBoolFalse(): void
    {
        foreach (['0', 'false', 'FALSE', ''] as $value) {
            $url     = 'https://api.expample.com?foo=' . $value;
            $query   = parse_url($url, PHP_URL_QUERY);
            $oParser = new RequestParser($query);

            $this->assertEquals(false, $oParser->getQueryParam('foo', 'bool'));
        }
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseAddonsAsBoolTrue(): void
    {
        foreach (['1', 'true', 'TRUE', 'foobar'] as $value) {
            $url     = 'https://api.expample.com?foo=' . $value;
            $query   = parse_url($url, PHP_URL_QUERY);
            $oParser = new RequestParser($query);

            $this->assertEquals(true, $oParser->getQueryParam('foo', 'bool'));
        }
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseAddonsInvalidType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $url     = 'https://api.expample.com?foo=bar&baz=5';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $this->assertEquals('bar', $oParser->getQueryParam('foo', 'stringa'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseFilter(): void
    {
        $url      = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1&'
            . 'filter[age][gt]=12&'
            . 'filter[type][in]=21,22,23&'
            . 'filter[bar]=baz&'
            . 'filter[baz][notnull]&'
            . 'filter[foo][null]&'
            . 'filter[foo][not]=2';
        $query    = parse_url($url, PHP_URL_QUERY);
        $oParser  = new RequestParser($query);
        $tFilters = $oParser->getFilter()->toArray();
        $this->assertCount(7, $tFilters);
        $this->assertEquals([21, 22, 23], $tFilters[2]->value);
        $this->assertEquals('bar', $tFilters[3]->param);
        $this->assertEquals(Filter::PARAM_EQ, $tFilters[3]->operator);
        $this->assertEquals('baz', $tFilters[3]->value);
        $this->assertTrue($tFilters[4]->isWhereNotNull());
        $this->assertFalse($tFilters[4]->isWhereNull());
        $this->assertTrue($tFilters[5]->isWhereNull());
        $this->assertFalse($tFilters[5]->isWhereNotNull());
        $this->assertTrue($tFilters[6]->isWhereNot());

        $this->assertEquals([], $oParser->getSort()->toArray());
        $this->assertNull($oParser->getLimit());
        $this->assertNull($oParser->getPage());
        $this->assertNull($oParser->getPhrase());
        $this->assertNull($oParser->getOffset());

        $this->assertCount(1, $oParser->getFilter()->getByParam('isFoo'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseSort()
    {
        $url      = 'https://api.expample.com?sort=id,-name';
        $query    = parse_url($url, PHP_URL_QUERY);
        $oParser  = new RequestParser($query);
        $tSorters = $oParser->getSort()->toArray();
        $this->assertEquals('id', $tSorters[0]->param);
        $this->assertEquals(Sort::DIRECTION_ASC, $tSorters[0]->direction);
        $this->assertEquals('name', $tSorters[1]->param);
        $this->assertEquals(Sort::DIRECTION_DESC, $tSorters[1]->direction);
        $this->assertEquals([], $oParser->getFilter()->toArray());
        $this->assertNull($oParser->getLimit());
        $this->assertNull($oParser->getPage());
        $this->assertNull($oParser->getPhrase());
        $this->assertNull($oParser->getOffset());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testRemoveQueryParam()
    {
        $url     = 'https://api.expample.com?foo=bar';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $this->assertEquals('bar', $oParser->getQueryParam('foo'));
        $oParser->removeQueryParam('foo');
        $this->assertEquals(null, $oParser->getQueryParam('foo'));
    }
}
