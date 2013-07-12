<?php

function __autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	require dirname(__FILE__) . '/../lib/' . $fileName;
}

/* Represents points in 3D space. */
class Point3D {
  public $x;
  public $y;
  public $z;
 
  public function __construct($x,$y,$z) {
	 $this->x = $x;
	 $this->y = $y;
	 $this->z = $z;
  }
 
  public function rotateX($angle) {
	 $rad = $angle * M_PI / 180;
	 $cosa = cos($rad);
	 $sina = sin($rad);
	 $y = $this->y * $cosa - $this->z * $sina;
	 $z = $this->y * $sina + $this->z * $cosa;
	 return new Point3D($this->x, $y, $z);
  }
 
  public function rotateY($angle) {
	 $rad = $angle * M_PI / 180;
	 $cosa = cos($rad);
	 $sina = sin($rad);
	 $z = $this->z * $cosa - $this->x * $sina;
	 $x = $this->z * $sina + $this->x * $cosa;
	 return new Point3D($x, $this->y, $z);
  }
 
  public function rotateZ($angle) {
	 $rad = $angle * M_PI / 180;
	 $cosa = cos($rad);
	 $sina = sin($rad);
	 $x = $this->x * $cosa - $this->y * $sina;
	 $y = $this->x * $sina + $this->y * $cosa;
	 return new Point3D($x, $y, $this->z);
  }
 
  public function project($width,$height,$fov,$viewerDistance) {
	 $factor = (float)($fov) / ($viewerDistance + $this->z);
	 $x = $this->x * $factor + ($width / 2);
	 $y = -$this->y * $factor + ($height / 2);
	 return new Point3D($x,$y,$this->z);
  }
}

$img_width  = CLI\Misc::cols();
$img_height = CLI\Misc::rows();

$width = .8;
$height = .8;
$depth = .8;

$hWidth = $width / 2;
$hHeight = $height / 2;
$hDepth = $depth / 2;
/* Define the 8 vertices of the cube. */
$vertices = array(
  new Point3D(-$hWidth, $hHeight,   -$hDepth),
  new Point3D($hWidth,  $hHeight,   -$hDepth),
  new Point3D($hWidth,  -$hHeight,  -$hDepth),
  new Point3D(-$hWidth, -$hHeight,  -$hDepth),
  new Point3D(-$hWidth, $hHeight,   $hDepth),
  new Point3D($hWidth,  $hHeight,   $hDepth),
  new Point3D($hWidth,  -$hHeight,  $hDepth),
  new Point3D(-$hWidth, -$hHeight,  $hDepth)
);

/* Define the vertices that compose each of the 6 faces. These numbers are
	indices to the vertex list defined above. */
$faces = array(array(0,1,2,3),array(1,5,6,2),array(5,4,7,6),array(4,0,3,7),array(0,4,5,1),array(3,2,6,7));

/* Assign random values for the angles that describe the cube orientation. */
$nnn = 0;
while(true) {
	CLI\Erase::screen();
	$angleX = -35 + ($nnn++);
	$angleZ = 15 + ($nnn++ / 2);
	$angleY = -30 +  + ($nnn++ / 3);

	/* It will store transformed vertices. */
	$t = array();
	$l = array();

	/* Transform all the vertices. */
	foreach( $vertices as $v ) {
		$t[] = $v->rotateX($angleX)->rotateY($angleY)->rotateZ($angleZ)->project($img_width,$img_height,256,6);
	}

		/* Transform all the vertices. */
	foreach( $vertices as $v ) {
		$l[] = $v->rotateX($angleX)->rotateY($angleY)->rotateZ($angleZ)->project($img_width,$img_height,256,9);
	}

	$avgZ = array();

	foreach( $faces as $index=>$f ) {
		$avgZ["$index"] = ($t[$f[0]]->z + $t[$f[1]]->z + $t[$f[2]]->z + $t[$f[3]]->z) / 4.0;
	}

	arsort($avgZ);

	foreach( $avgZ as $index=>$z ) {
		$f = $faces[$index];

		$points = array(
			$t[$f[0]]->x,$t[$f[0]]->y,
			$t[$f[1]]->x,$t[$f[1]]->y,
			$t[$f[2]]->x,$t[$f[2]]->y,
			$t[$f[3]]->x,$t[$f[3]]->y
		);

		CLI\Graphics::line($t[$f[0]]->x, $t[$f[0]]->y, $t[$f[1]]->x, $t[$f[1]]->y);
		CLI\Graphics::line($t[$f[1]]->x, $t[$f[1]]->y, $t[$f[2]]->x, $t[$f[2]]->y);
		CLI\Graphics::line($t[$f[2]]->x, $t[$f[2]]->y, $t[$f[3]]->x, $t[$f[3]]->y);
		CLI\Graphics::line($t[$f[3]]->x, $t[$f[3]]->y, $t[$f[0]]->x, $t[$f[0]]->y);


		$points = array(
			$l[$f[0]]->x,$l[$f[0]]->y,
			$l[$f[1]]->x,$l[$f[1]]->y,
			$l[$f[2]]->x,$l[$f[2]]->y,
			$l[$f[3]]->x,$l[$f[3]]->y
		);

		CLI\Graphics::line($l[$f[0]]->x, $l[$f[0]]->y, $l[$f[1]]->x, $l[$f[1]]->y, '.');
		CLI\Graphics::line($l[$f[1]]->x, $l[$f[1]]->y, $l[$f[2]]->x, $l[$f[2]]->y, '.');
		CLI\Graphics::line($l[$f[2]]->x, $l[$f[2]]->y, $l[$f[3]]->x, $l[$f[3]]->y, '.');
		CLI\Graphics::line($l[$f[3]]->x, $l[$f[3]]->y, $l[$f[0]]->x, $l[$f[0]]->y, '.');

	}

	usleep(50000);
}
