<?php

namespace Codefog\MemberExportBundle\Tests\Exporter;

use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Codefog\MemberExportBundle\Exporter\CsvExporter;
use Haste\IO\Writer\CsvFileWriter;

class CsvExporterTest extends ExporterTestCase
{
    public function testInstantiation()
    {
        static::assertInstanceOf(CsvExporter::class, new CsvExporter($this->mockFramework()));
    }

    public function testGetAlias()
    {
        $exporter = new CsvExporter($this->mockFramework());

        static::assertSame('csv', $exporter->getAlias());
    }

    public function testExport()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(CsvFileWriter::class, true, $exportComplete)
        );

        $exporter = new CsvExporter($framework);
        $exporter->export($this->getExportConfig());

        static::assertTrue($exportComplete);
    }

    public function testExportNoData()
    {
        $this->expectException(ExportException::class);

        $exporter = new CsvExporter($this->mockFramework($this->getExportNoDataAdapters()));
        $exporter->export($this->getExportConfig());
    }

    public function testExportNoFields()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(CsvFileWriter::class, false, $exportComplete)
        );

        $exporter = new CsvExporter($framework);
        $exporter->export($this->getExportConfig());

        static::assertTrue($exportComplete);
    }
}
