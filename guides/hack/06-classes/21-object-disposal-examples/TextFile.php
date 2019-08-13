<?hh // strict

namespace Hack\UserDocumentation\Classes\ObjectDisposal\Examples\TextFile;

class TextFile implements \IDisposable {
  private ?int $fileHandle = null;
  private bool $openFlag = false;
  private string $fileName;
  private string $openMode;

  public function __construct(string $fileName, string $openMode) {
    $this->fileHandle = 55;  // open file somehow and store handle
    $this->openFlag = true;  // file is open
    $this->fileName = $fileName;
    $this->openMode = $openMode;
  }

  public function close(): void {
    if ($this->openFlag === false) {
      return;
    }

    // ... somehow close the file
    $this->fileHandle = null;
    $this->openFlag = false;

    echo "Closed file $this->fileName\n";
  }

  public function __toString(): string {
    return
    'fileName: ' . $this->fileName
    . ', openMode: ' . $this->openMode
    . ', fileHandle: '
    . (($this->fileHandle === null) ? "null" : $this->fileHandle)
    . ', openFlag: ' . (($this->openFlag) ? "True" : "False");
  }

  public function __dispose(): void {
     echo "Inside __dispose\n";
     $this->close();
  }

  <<__ReturnDisposable>>
  public static function open_TextFile(string $fileName, string $openMode): TextFile {
    return new TextFile($fileName, $openMode);
  }

  public function is_same_TextFile(<<__AcceptDisposable>> TextFile $t): bool {
    return $this->fileHandle === $t->fileHandle;
  }

  // other methods, such as read and write
}

<<__EntryPoint>>
function main(): void {
  using ($f1 = new TextFile("file1.txt", "rw")) {
//  echo "\$f1 is >" . $f1 . "<\n";  // usage not permitted
    echo "\$f1 is >" . $f1->__toString() . "<\n";
    // work with the file
    $f1->close();  // close explicitly
    $f1->close();  // try to close again
  } // dispose called here

  using ($f2 = new TextFile("file2.txt", "rw")) {
    echo "\$f2 is >" . $f2->__toString() . "<\n";
    // work with the file
    // no explicit close
  } // dispose called here

  using ($f3 = TextFile::open_TextFile("file3.txt", "rw")) {
    echo "\$f3 is >" . $f3->__toString() . "<\n";
    // work with the file
    // no explicit close
  } // dispose called here

  using $f4 = TextFile::open_TextFile("file4.txt", "rw");
  echo "\$f4 is >" . $f4->__toString() . "<\n";
  using $f5 = new TextFile("file5.txt", "rw");
  echo "\$f5 is >" . $f5->__toString() . "<\n";
    // work with both files
    // no explicit close
}   // dispose called here for both $f4 and $f5
