<html>
<head>
<title>Les Houches Accord</title>
</head>
<body>

<script language=javascript type=text/javascript>
function stopRKey(evt) {
var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target :((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=="text"))
{return false;}
}

document.onkeypress = stopRKey;
</script>
<?php
if($_POST['saved'] == 1) {
if($_POST['filepath'] != "files/") {
echo "<font color='red'>SETTINGS SAVED TO FILE</font><br/><br/>"; }
else {
echo "<font color='red'>NO FILE SELECTED YET.. PLEASE DO SO </font><a href='SaveSettings.php'>HERE</a><br/><br/>"; }
}
?>

<form method='post' action='LesHouchesAccord.php'>

<h2>Les Houches Accord</h2>

The Les Houches Accord for user processes [<a href="Bibliography.php" target="page">Boo01</a>] is the 
standard way to input parton-level information from a 
matrix-elements-based generator into PYTHIA. The conventions for 
which information should be stored has been defined in a Fortran context, 
as two commonblocks. Here a C++ equivalent is defined, as two separate 
classes.

<p/>
The <code>LHAinit</code> and <code>LHAevnt</code> classes are base classes, 
containing reading and printout functions, plus a pure virtual function 
each. Derived classes have to provide these two virtual functions to do 
the actual work. The existing derived classes are for reading information 
from a Les Houches Event File or from the respective Fortran commonblock.

<p/>
Normally, pointers to objects of the derived classes should be handed
in with the <?php $filepath = $_GET["filepath"];
echo "<a href='ProgramFlow.php?filepath=".$filepath."' target='page'>";?>
<code>pythia.init( LHAinit*, LHAevnt*)</code></a> method. However, 
with the Les Houches Event File format a filename can replace the 
two pointers, see below. 

<h3>Initialization</h3>

The <code>LHAinit</code> class stores information equivalent to the 
<code>/HEPRUP/</code> commonblock, as required to initialize the event 
generation chain. The main difference is that the vector container 
now allows a flexible number of subprocesses to be defined. For the 
rest, names have been modified, since the 6-character-limit does not 
apply, and variables have been regrouped for clarity, but nothing 
fundamental is changed.

<p/>
The pure virtual function <code>set()</code> has to be implemented in the 
derived class, to set relevant information when called. It should
return <code>false</code> if it fails to set the info.

<p/>
Inside <code>set()</code>, such information can be set by the following 
methods:
<p/><code>method&nbsp; </code><strong> beamA( identity, energy, pdfGroup, pdfSet) &nbsp;</strong> <br/>
sets the properties of the first incoming beam (cf. the Fortran
<code>IDBMUP(1), EBMUP(1), PDFGUP(1), PDFSUP(1)</code>), and similarly 
a <code>beamB</code> method exists. The parton distribution information 
defaults to zero, meaning that internal sets are used.
  
<p/><code>method&nbsp; </code><strong> strategy( choice) &nbsp;</strong> <br/>
sets the event weighting and cross section  strategy 
(cf. <code>IDWTUP</code>).
  
<p/><code>method&nbsp; </code><strong> process( idProcess, crossSection, crossSectionError, crossSectionMaximum) &nbsp;</strong> <br/>
sets info on an allowed process (cf. <code>LPRUP, XSECUP, XERRUP, 
XMAXUP</code>). 
Each new call will append one more entry to the list of processes.
  

<p/>
Information is handed back by the following methods:
<p/><code>method&nbsp; </code><strong> idBeamA(), eBeamA(), pdfGroupBeamA(), pdfSetBeamA() &nbsp;</strong> <br/>
and similarly with <i>A -> B</i>, for the two beam particles.
  
<p/><code>method&nbsp; </code><strong> strategy() &nbsp;</strong> <br/>
for the strategy choice.
  
<p/><code>method&nbsp; </code><strong> size() &nbsp;</strong> <br/>
for the number of subprocesses.
  
<p/><code>method&nbsp; </code><strong> idProcess(i), xSec(i), xErr(i), xMax(i) &nbsp;</strong> <br/>
for process <code>i</code> in the range <code>0 &lt;= i &lt; 
size()</code>.   
  

<p/>
The information can also be printed using the <code>list()</code>
method, e.g. <code>LHAinitObject->list()</code>.
This is automatically done by the <code>pythia.init</code> call,
unless the runtime interface to PYTHIA 6 is being used, in which
case that program is intended to print the information.

<h3>Event input</h3>

The <code>LHAevnt</code> class stores information equivalent to the 
<code>/HEPEUP/</code> commonblock, as required to hand in the next 
parton-level configuration for complete event generation. The main 
difference is that the vector container now allows a flexible number 
of partons to be defined. For the rest, names have been modified, 
since the 6-character-limit does not apply, and variables have been 
regrouped for clarity, but nothing fundamental is changed.

<p/>
The Les Houches standard is based on Fortran arrays beginning with
index 1, and mother information is defined accordingly. In order to 
be compatible with this convention, the zeroth line of the C++ particle
array is kept empty, so that index 1 also here corresponds to the first
particle. One small incompatibility is that the <code>size()</code> 
method returns the full size of the particle array, including the 
empty zeroth line, and thus is one larger than the true number of 
particles (<code>NUP</code>). 

<p/>
The pure virtual function <code>set()</code> has to be implemented in 
the derived class, to set relevant information when called. It should
return <code>false</code> if it fails to set the info, e.g. if the 
supply of events in a file is exhausted.

<p/>
Inside <code>set()</code>, such information can be set by the following
methods:
<p/><code>method&nbsp; </code><strong> process( idProcess, weight, scale, alphaQED, alphaQCD) &nbsp;</strong> <br/>
tells which kind of process occured, with what weight, at what scale, 
and which <i>alpha_EM</i> and <i>alpha_strong</i> were used
(cf. <code>IDPRUP, XWTGUP, SCALUP, AQEDUP, AQCDUP</code>). This method 
also resets the size of the particle list, and adds the empty zeroth 
line, so it has to be called before the <code>particle</code> method below.
  
<p/><code>method&nbsp; </code><strong> particle( id, status, mother1, mother2, colourTag1, colourTag2, p_x, p_y, p_z, e, m, tau, spin) &nbsp;</strong> <br/>
gives the properties of the next particle handed in (cf. <code>IDUP, ISTUP, 
MOTHUP(1,..), MOTHUP(2,..), ICOLUP(1,..), ICOLUP(2,..),  PUP(J,..), 
VTIMUP, SPINUP</code>) .
  

<p/>
Information is handed back by the following methods:
<p/><code>method&nbsp; </code><strong> idProc(), weight(), scale(), alphaQED(), alphaQCD() &nbsp;</strong> <br/>
  
<p/><code>method&nbsp; </code><strong> size() &nbsp;</strong> <br/>
for the size of the particle array, which is one larger than the number 
of particles in the event, since the zeroth entry is kept empty 
(see above).
  
<p/><code>method&nbsp; </code><strong> id(i), status(i), mother1(i), mother2(i), col1(i), col2(i),px(i), py(i), pz(i), e(i), m(i), tau(i), spin(i) &nbsp;</strong> <br/>
for particle <code>i</code> in the range 
<code>0 &lt;= i &lt; size()</code>. (But again note that 
<code>i = 0</code> is an empty line, so the true range begins at 1.)   
  

<p/>
In the Les Houches Event File proposal [<a href="Bibliography.php" target="page">Alw06</a>] an extension to 
include information on the parton densities of the colliding partons
was suggested. This optional further information can be set by
<p/><code>method&nbsp; </code><strong> pdf( id1, id2, x1, x2, scalePDF, xpdf1, xpdf2) &nbsp;</strong> <br/>
which gives the flavours , the <i>x</i> and the <ie>Q</i> scale 
(in GeV) at which the parton densities <i>x*f_i(x, Q)</i> have been
evaluated.
  

<p/>
This information is returned by the methods
<p/><code>method&nbsp; </code><strong> pdfIsSet(), id1(), id2(), x1(), x2(), scalePDF(), xpdf1(), xpdf2() &nbsp;</strong> <br/>
where the first one tells whether this optional information has been set
for the current event. (<code>pdf</code> must be called after the
<code>process</code> call of the event for this to work.)
  

<p/>
The information can also be printed using the <code>list()</code>
method, e.g. <code>LHAevntObject->list()</code>.
In cases where the <code>LHAevntObject</code> is not available to the
user, the <code>pythia.LHAevntList()</code> method can be used, which 
is a wrapper for the above. 

<h3>An interface to Les Houches Event Files</h3>

The new Les Houches Event File (LHEF) standard [<a href="Bibliography.php" target="page">Alw06</a>] specifies 
a format where a single file packs initialization and event information.
This is likely to become the most frequently used procedure to process
external parton-level events in Pythia. Therefore a special 
<?php $filepath = $_GET["filepath"];
echo "<a href='ProgramFlow.php?filepath=".$filepath."' target='page'>";?>
<code>pythia.init("filename")</code></a>
initialization option exists, where the LHEF name is provided as single
input. Internally this name is then used to create instances of two derived 
classes, <code>LHAinitLHEF</code> and <code>LHAevntLHEF</code>. Both of
them are allowed to read from the same LHEF, first the former and then 
the latter.

<p/>
An example how to generate events from a LHEF is found in 
<code>main14.cc</code>.

<h3>A runtime Fortran interface</h3>

The runtime Fortran interface requires linking to an external Fortran
code. In order to avoid problems with unresolved external references
when this interface is not used, the code has been put in a separate
<code>LHAFortran.h</code> file, that is not included in any of the
other library files. Instead it should be included in the 
user-supplied main program, together with the implementation of two
methods below that call the Fortran program to do its part of the job. 

<p/>
The <code>LHAinitFortran</code> class derives from <code>LHAinit</code>. 
It reads initialization information from the Les Houches standard 
Fortran commonblock, assuming this commonblock behaves like an 
<code>extern "C" struct</code> named <code>heprup_</code>. (Note the final
underscore, to match how the gcc compiler internally names Fortran
files.) 

<p/>
Initialization is with
<pre>
    LHAinitFortran lhaInit();
</pre>
i.e. does not require any arguments. 

<p/>
The user has to supply an implementation of the <code>fillHepRup()</code>
method, that is to do the actual calling of the external Fortran routine(s)
that fills the <code>HEPRUP</code> commonblock. The translation of this
information to the C++ structure is provided by the existing 
<code>set()</code> code. 

<p/>
The <code>LHAevntFortran</code> class derives from <code>LHAevnt</code>. 
It reads information on the next event, stored in the Les Houches 
standard Fortran commonblock, assuming this commonblock behaves like 
an <code>extern "C" struct</code> named <code>hepeup_</code>.

<p/>
Initialization is with
<pre>
    LHAevntFortran lhaEvnt();
</pre>
i.e. does not require any arguments. 

<p/>
The user has to supply an implementation of the <code>fillHepEup()</code>
method, that is to do the actual calling of the external Fortran routine(s)
that fills the <code>HEPEUP</code> commonblock. The translation of this
information to the C++ structure is provided by the existing 
<code>set()</code> code. 

<p/>
See further 
<?php $filepath = $_GET["filepath"];
echo "<a href='AccessPYTHIA6Processes.php?filepath=".$filepath."' target='page'>";?>here</a> for 
information how PYTHIA 6.4 can be linked to make use of this facility. 
The example <code>main11</code> and <code>main12</code> programs illustrate 
how it can be used. 

<h3>Other examples</h3>

A special <code>strategy = 10</code> (not present in the <code>IDWTUP</code> 
specification) has been added. It takes a given partonic input, 
no questions asked, and hadronizes it, i.e. does string fragmentation 
and decay. Thereby the normal process-level and parton-level machineries 
are bypassed, to the largest extent possible. (Some parts are used, 
e.g. first to translate the Les Houches event to the process record 
and later to the event record.) Such an option can therefore be used 
to feed in ready-made parton-level configurations, without needing to 
specify where these come from, i.e. there need be no beams or any such 
explicit information, but of course the user must have taken care of it
beforehand. 

<p/>
An example how this can be used for toy studies is found in 
<code>main15.cc</code>.

</body>
</html>

<!-- Copyright C 2007 Torbjorn Sjostrand -->