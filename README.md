# Zip Stream

This library is for generating large zip files with a low memory footprint. The contents of the
zip file are never stored in memory at once. Everything is written using streams. This library
is for writing zip files only and has no reading capabilities.

## Getting Started

The only requirements are PHP 7.0+ and the zlib extension (almost always enabled).

### Installing

`composer require jdwil/zip-stream`

## Running tests

`./vendor/bin/phpspec run`

## Examples

### Basic Usage

```$xslt
$zipStream = ZipStream::forFile('/path/to/file.zip');

// Add a file from disk
$zipStream->addFileFromDisk('foo.txt', '/path/to/foo.txt');

// Add a file from a stream
$stream = ReadStream::forFile('/path/to/bar.txt');
$zipStream->addFileFromStream('bar.txt', $stream);

// Add arbirary data
$zipStream->addFile('baz.txt', 'some arbitrary text');

// Always close the Zip Stream
$zipStream->close();
```

### Dealing with huge data sets
```$xslt
$zipStream = ZipStream::forFile('/path/to/file.zip');
$zipStream->beginFile('foo.txt');
while ($data = $somePdoStatement->fetch()) {
  $zipStream->addFilePart(implode(',', $row));
}
$zipStream->endFile();
$zipStream->close();
```

### Stream a ZIP file directly to the user

The file is sent as it is being built, so the download begins immediately for the user.

```$xslt
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="foo.zip"');
header('Content-Transfer-Encoding: binary');

$zipStream = ZipStream::forFile('php://output');
// Build your zip file
$zipStream->close();
```

## Authors

JD Williams <me@jdwilliams.xyz>