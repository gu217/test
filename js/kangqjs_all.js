// begin ../ajaxslt/util.js








function xpathLog(msg) {};
function xsltLog(msg) {};
function xsltLogXml(msg) {};


function assert(b) {
  if (!b) {
    throw "Assertion failed";
  }
}




function stringSplit(s, c) {
  var a = s.indexOf(c);
  if (a == -1) {
    return [ s ];
  }
  var parts = [];
  parts.push(s.substr(0,a));
  while (a != -1) {
    var a1 = s.indexOf(c, a + 1);
    if (a1 != -1) {
      parts.push(s.substr(a + 1, a1 - a - 1));
    } else {
      parts.push(s.substr(a + 1));
    }
    a = a1;
  }
  return parts;
}




function xmlImportNode(doc, node) {
  if (node.nodeType == DOM_TEXT_NODE) {
    return domCreateTextNode(doc, node.nodeValue);

  } else if (node.nodeType == DOM_CDATA_SECTION_NODE) {
    return domCreateCDATASection(doc, node.nodeValue);

  } else if (node.nodeType == DOM_ELEMENT_NODE) {
    var newNode = domCreateElement(doc, node.nodeName);
    for (var i = 0; i < node.attributes.length; ++i) {
      var an = node.attributes[i];
      var name = an.nodeName;
      var value = an.nodeValue;
      domSetAttribute(newNode, name, value);
    }

    for (var c = node.firstChild; c; c = c.nextSibling) {
      var cn = arguments.callee(doc, c);
      domAppendChild(newNode, cn);
    }

    return newNode;

  } else {
    return domCreateComment(doc, node.nodeName);
  }
}












function Set() {
  this.keys = [];
}

Set.prototype.size = function() {
  return this.keys.length;
}


Set.prototype.add = function(key, opt_value) {
  var value = opt_value || 1;
  if (!this.contains(key)) {
    this[':' + key] = value;
    this.keys.push(key);
  }
}


Set.prototype.set = function(key, opt_value) {
  var value = opt_value || 1;
  if (!this.contains(key)) {
    this[':' + key] = value;
    this.keys.push(key);
  } else {
    this[':' + key] = value;
  }
}





Set.prototype.inc = function(key) {
  if (!this.contains(key)) {
    this[':' + key] = 1;
    this.keys.push(key);
  } else {
    this[':' + key]++;
  }
}

Set.prototype.get = function(key) {
  if (this.contains(key)) {
    return this[':' + key];
  } else {
    var undefined;
    return undefined;
  }
}


Set.prototype.remove = function(key) {
  if (this.contains(key)) {
    delete this[':' + key];
    removeFromArray(this.keys, key, true);
  }
}


Set.prototype.contains = function(entry) {
  return typeof this[':' + entry] != 'undefined';
}


Set.prototype.items = function() {
  var list = [];
  for (var i = 0; i < this.keys.length; ++i) {
    var k = this.keys[i];
    var v = this[':' + k];
    list.push(v);
  }
  return list;
}




Set.prototype.map = function(f) {
  for (var i = 0; i < this.keys.length; ++i) {
    var k = this.keys[i];
    f.call(this, k, this[':' + k]);
  }
}

Set.prototype.clear = function() {
  for (var i = 0; i < this.keys.length; ++i) {
    delete this[':' + this.keys[i]];
  }
  this.keys.length = 0;
}




function mapExec(array, func) {
  for (var i = 0; i < array.length; ++i) {
    func.call(this, array[i], i);
  }
}



function mapExpr(array, func) {
  var ret = [];
  for (var i = 0; i < array.length; ++i) {
    ret.push(func(array[i]));
  }
  return ret;
};


function reverseInplace(array) {
  for (var i = 0; i < array.length / 2; ++i) {
    var h = array[i];
    var ii = array.length - i - 1;
    array[i] = array[ii];
    array[ii] = h;
  }
}



function removeFromArray(array, value, opt_notype) {
  var shift = 0;
  for (var i = 0; i < array.length; ++i) {
    if (array[i] === value || (opt_notype && array[i] == value)) {
      array.splice(i--, 1);
      shift++;
    }
  }
  return shift;
}


function copyArray(dst, src) {
  for (var i = 0; i < src.length; ++i) {
    dst.push(src[i]);
  }
}




function xmlValue(node) {
  if (!node) {
    return '';
  }

  var ret = '';
  if (node.nodeType == DOM_TEXT_NODE ||
      node.nodeType == DOM_CDATA_SECTION_NODE ||
      node.nodeType == DOM_ATTRIBUTE_NODE) {
    ret += node.nodeValue;

  } else if (node.nodeType == DOM_ELEMENT_NODE ||
             node.nodeType == DOM_DOCUMENT_NODE ||
             node.nodeType == DOM_DOCUMENT_FRAGMENT_NODE) {
    for (var i = 0; i < node.childNodes.length; ++i) {
      ret += arguments.callee(node.childNodes[i]);
    }
  }
  return ret;
}


function xmlText(node, opt_cdata) {
  var buf = [];
  xmlTextR(node, buf, opt_cdata);
  return buf.join('');
}

function xmlTextR(node, buf, cdata) {
  if (node.nodeType == DOM_TEXT_NODE) {
    buf.push(xmlEscapeText(node.nodeValue));

  } else if (node.nodeType == DOM_CDATA_SECTION_NODE) {
    if (cdata) {
      buf.push(node.nodeValue);
    } else {
      buf.push('<![CDATA[' + node.nodeValue + ']]>');
    }

  } else if (node.nodeType == DOM_COMMENT_NODE) {
    buf.push('<!--' + node.nodeValue + '-->');

  } else if (node.nodeType == DOM_ELEMENT_NODE) {
    buf.push('<' + xmlFullNodeName(node));
    for (var i = 0; i < node.attributes.length; ++i) {
      var a = node.attributes[i];
      if (a && a.nodeName && a.nodeValue) {
        buf.push(' ' + xmlFullNodeName(a) + '="' +
                 xmlEscapeAttr(a.nodeValue) + '"');
      }
    }

    if (node.childNodes.length == 0) {
      buf.push('/>');
    } else {
      buf.push('>');
      for (var i = 0; i < node.childNodes.length; ++i) {
        arguments.callee(node.childNodes[i], buf, cdata);
      }
      buf.push('</' + xmlFullNodeName(node) + '>');
    }

  } else if (node.nodeType == DOM_DOCUMENT_NODE ||
             node.nodeType == DOM_DOCUMENT_FRAGMENT_NODE) {
    for (var i = 0; i < node.childNodes.length; ++i) {
      arguments.callee(node.childNodes[i], buf, cdata);
    }
  }
}

function xmlFullNodeName(n) {
  if (n.prefix && n.nodeName.indexOf(n.prefix + ':') != 0) {
    return n.prefix + ':' + n.nodeName;
  } else {
    return n.nodeName;
  }
}




function xmlEscapeText(s) {
  return ('' + s).replace(/&/g, '&amp;').replace(/</g, '&lt;').
    replace(/>/g, '&gt;');
}





function xmlEscapeAttr(s) {
  return xmlEscapeText(s).replace(/\"/g, '&quot;');
}



function xmlEscapeTags(s) {
  return s.replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

 
function xmlOwnerDocument(node) {
  if (node.nodeType == DOM_DOCUMENT_NODE) {
    return node;
  } else {
    return node.ownerDocument;
  }
}


function domGetAttribute(node, name) {
  return node.getAttribute(name);
}

function domSetAttribute(node, name, value) {
  return node.setAttribute(name, value);
}

function domRemoveAttribute(node, name) {
  return node.removeAttribute(name);
}

function domAppendChild(node, child) {
  return node.appendChild(child);
}

function domRemoveChild(node, child) {
  return node.removeChild(child);
}

function domReplaceChild(node, newChild, oldChild) {
  return node.replaceChild(newChild, oldChild);
}

function domInsertBefore(node, newChild, oldChild) {
  return node.insertBefore(newChild, oldChild);
}

function domRemoveNode(node) {
  return domRemoveChild(node.parentNode, node);
}

function domCreateTextNode(doc, text) {
  return doc.createTextNode(text);
}

function domCreateElement(doc, name) {
  return doc.createElement(name);
}

function domCreateAttribute(doc, name) {
  return doc.createAttribute(name);
}

function domCreateCDATASection(doc, data) {
  return doc.createCDATASection(data);
}

function domCreateComment(doc, text) {
  return doc.createComment(text);
}

function domCreateDocumentFragment(doc) {
  return doc.createDocumentFragment();
}

function domGetElementById(doc, id) {
  return doc.getElementById(id);
}


function windowSetInterval(win, fun, time) {
  return win.setInterval(fun, time);
}

function windowClearInterval(win, id) {
  return win.clearInterval(id);
}

// end of ../ajaxslt/util.js

// begin ../ajaxslt/xmltoken.js












var REGEXP_UNICODE = function() {
  var tests = [' ', '\u0120', -1,  
               '!', '\u0120', -1,
               '\u0120', '\u0120', 0,
               '\u0121', '\u0120', -1,
               '\u0121', '\u0120|\u0121', 0,
               '\u0122', '\u0120|\u0121', -1,
               '\u0120', '[\u0120]', 0,  
               '\u0121', '[\u0120]', -1,
               '\u0121', '[\u0120\u0121]', 0,  
               '\u0122', '[\u0120\u0121]', -1,
               '\u0121', '[\u0120-\u0121]', 0,  
               '\u0122', '[\u0120-\u0121]', -1];
  for (var i = 0; i < tests.length; i += 3) {
    if (tests[i].search(new RegExp(tests[i + 1])) != tests[i + 2]) {
      return false;
    }
  }
  return true;
}();



var XML_S = '[ \t\r\n]+';
var XML_EQ = '(' + XML_S + ')?=(' + XML_S + ')?';
var XML_CHAR_REF = '&#[0-9]+;|&#x[0-9a-fA-F]+;';



var XML10_VERSION_INFO = XML_S + 'version' + XML_EQ + '("1\\.0"|' + "'1\\.0')";
var XML10_BASE_CHAR = (REGEXP_UNICODE) ?
  '\u0041-\u005a\u0061-\u007a\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u00ff' +
  '\u0100-\u0131\u0134-\u013e\u0141-\u0148\u014a-\u017e\u0180-\u01c3' +
  '\u01cd-\u01f0\u01f4-\u01f5\u01fa-\u0217\u0250-\u02a8\u02bb-\u02c1\u0386' +
  '\u0388-\u038a\u038c\u038e-\u03a1\u03a3-\u03ce\u03d0-\u03d6\u03da\u03dc' +
  '\u03de\u03e0\u03e2-\u03f3\u0401-\u040c\u040e-\u044f\u0451-\u045c' +
  '\u045e-\u0481\u0490-\u04c4\u04c7-\u04c8\u04cb-\u04cc\u04d0-\u04eb' +
  '\u04ee-\u04f5\u04f8-\u04f9\u0531-\u0556\u0559\u0561-\u0586\u05d0-\u05ea' +
  '\u05f0-\u05f2\u0621-\u063a\u0641-\u064a\u0671-\u06b7\u06ba-\u06be' +
  '\u06c0-\u06ce\u06d0-\u06d3\u06d5\u06e5-\u06e6\u0905-\u0939\u093d' +
  '\u0958-\u0961\u0985-\u098c\u098f-\u0990\u0993-\u09a8\u09aa-\u09b0\u09b2' +
  '\u09b6-\u09b9\u09dc-\u09dd\u09df-\u09e1\u09f0-\u09f1\u0a05-\u0a0a' +
  '\u0a0f-\u0a10\u0a13-\u0a28\u0a2a-\u0a30\u0a32-\u0a33\u0a35-\u0a36' +
  '\u0a38-\u0a39\u0a59-\u0a5c\u0a5e\u0a72-\u0a74\u0a85-\u0a8b\u0a8d' +
  '\u0a8f-\u0a91\u0a93-\u0aa8\u0aaa-\u0ab0\u0ab2-\u0ab3\u0ab5-\u0ab9' +
  '\u0abd\u0ae0\u0b05-\u0b0c\u0b0f-\u0b10\u0b13-\u0b28\u0b2a-\u0b30' +
  '\u0b32-\u0b33\u0b36-\u0b39\u0b3d\u0b5c-\u0b5d\u0b5f-\u0b61\u0b85-\u0b8a' +
  '\u0b8e-\u0b90\u0b92-\u0b95\u0b99-\u0b9a\u0b9c\u0b9e-\u0b9f\u0ba3-\u0ba4' +
  '\u0ba8-\u0baa\u0bae-\u0bb5\u0bb7-\u0bb9\u0c05-\u0c0c\u0c0e-\u0c10' +
  '\u0c12-\u0c28\u0c2a-\u0c33\u0c35-\u0c39\u0c60-\u0c61\u0c85-\u0c8c' +
  '\u0c8e-\u0c90\u0c92-\u0ca8\u0caa-\u0cb3\u0cb5-\u0cb9\u0cde\u0ce0-\u0ce1' +
  '\u0d05-\u0d0c\u0d0e-\u0d10\u0d12-\u0d28\u0d2a-\u0d39\u0d60-\u0d61' +
  '\u0e01-\u0e2e\u0e30\u0e32-\u0e33\u0e40-\u0e45\u0e81-\u0e82\u0e84' +
  '\u0e87-\u0e88\u0e8a\u0e8d\u0e94-\u0e97\u0e99-\u0e9f\u0ea1-\u0ea3\u0ea5' +
  '\u0ea7\u0eaa-\u0eab\u0ead-\u0eae\u0eb0\u0eb2-\u0eb3\u0ebd\u0ec0-\u0ec4' +
  '\u0f40-\u0f47\u0f49-\u0f69\u10a0-\u10c5\u10d0-\u10f6\u1100\u1102-\u1103' +
  '\u1105-\u1107\u1109\u110b-\u110c\u110e-\u1112\u113c\u113e\u1140\u114c' +
  '\u114e\u1150\u1154-\u1155\u1159\u115f-\u1161\u1163\u1165\u1167\u1169' +
  '\u116d-\u116e\u1172-\u1173\u1175\u119e\u11a8\u11ab\u11ae-\u11af' +
  '\u11b7-\u11b8\u11ba\u11bc-\u11c2\u11eb\u11f0\u11f9\u1e00-\u1e9b' +
  '\u1ea0-\u1ef9\u1f00-\u1f15\u1f18-\u1f1d\u1f20-\u1f45\u1f48-\u1f4d' +
  '\u1f50-\u1f57\u1f59\u1f5b\u1f5d\u1f5f-\u1f7d\u1f80-\u1fb4\u1fb6-\u1fbc' +
  '\u1fbe\u1fc2-\u1fc4\u1fc6-\u1fcc\u1fd0-\u1fd3\u1fd6-\u1fdb\u1fe0-\u1fec' +
  '\u1ff2-\u1ff4\u1ff6-\u1ffc\u2126\u212a-\u212b\u212e\u2180-\u2182' +
  '\u3041-\u3094\u30a1-\u30fa\u3105-\u312c\uac00-\ud7a3' :
  'A-Za-z';
var XML10_IDEOGRAPHIC = (REGEXP_UNICODE) ?
  '\u4e00-\u9fa5\u3007\u3021-\u3029' :
  '';
var XML10_COMBINING_CHAR = (REGEXP_UNICODE) ?
  '\u0300-\u0345\u0360-\u0361\u0483-\u0486\u0591-\u05a1\u05a3-\u05b9' +
  '\u05bb-\u05bd\u05bf\u05c1-\u05c2\u05c4\u064b-\u0652\u0670\u06d6-\u06dc' +
  '\u06dd-\u06df\u06e0-\u06e4\u06e7-\u06e8\u06ea-\u06ed\u0901-\u0903\u093c' +
  '\u093e-\u094c\u094d\u0951-\u0954\u0962-\u0963\u0981-\u0983\u09bc\u09be' +
  '\u09bf\u09c0-\u09c4\u09c7-\u09c8\u09cb-\u09cd\u09d7\u09e2-\u09e3\u0a02' +
  '\u0a3c\u0a3e\u0a3f\u0a40-\u0a42\u0a47-\u0a48\u0a4b-\u0a4d\u0a70-\u0a71' +
  '\u0a81-\u0a83\u0abc\u0abe-\u0ac5\u0ac7-\u0ac9\u0acb-\u0acd\u0b01-\u0b03' +
  '\u0b3c\u0b3e-\u0b43\u0b47-\u0b48\u0b4b-\u0b4d\u0b56-\u0b57\u0b82-\u0b83' +
  '\u0bbe-\u0bc2\u0bc6-\u0bc8\u0bca-\u0bcd\u0bd7\u0c01-\u0c03\u0c3e-\u0c44' +
  '\u0c46-\u0c48\u0c4a-\u0c4d\u0c55-\u0c56\u0c82-\u0c83\u0cbe-\u0cc4' +
  '\u0cc6-\u0cc8\u0cca-\u0ccd\u0cd5-\u0cd6\u0d02-\u0d03\u0d3e-\u0d43' +
  '\u0d46-\u0d48\u0d4a-\u0d4d\u0d57\u0e31\u0e34-\u0e3a\u0e47-\u0e4e\u0eb1' +
  '\u0eb4-\u0eb9\u0ebb-\u0ebc\u0ec8-\u0ecd\u0f18-\u0f19\u0f35\u0f37\u0f39' +
  '\u0f3e\u0f3f\u0f71-\u0f84\u0f86-\u0f8b\u0f90-\u0f95\u0f97\u0f99-\u0fad' +
  '\u0fb1-\u0fb7\u0fb9\u20d0-\u20dc\u20e1\u302a-\u302f\u3099\u309a' :
  '';
var XML10_DIGIT = (REGEXP_UNICODE) ?
  '\u0030-\u0039\u0660-\u0669\u06f0-\u06f9\u0966-\u096f\u09e6-\u09ef' +
  '\u0a66-\u0a6f\u0ae6-\u0aef\u0b66-\u0b6f\u0be7-\u0bef\u0c66-\u0c6f' +
  '\u0ce6-\u0cef\u0d66-\u0d6f\u0e50-\u0e59\u0ed0-\u0ed9\u0f20-\u0f29' :
  '0-9';
var XML10_EXTENDER = (REGEXP_UNICODE) ?
  '\u00b7\u02d0\u02d1\u0387\u0640\u0e46\u0ec6\u3005\u3031-\u3035' +
  '\u309d-\u309e\u30fc-\u30fe' :
  '';
var XML10_LETTER = XML10_BASE_CHAR + XML10_IDEOGRAPHIC;
var XML10_NAME_CHAR = XML10_LETTER + XML10_DIGIT + '\\._:' +
                      XML10_COMBINING_CHAR + XML10_EXTENDER + '-';
var XML10_NAME = '[' + XML10_LETTER + '_:][' + XML10_NAME_CHAR + ']*';

var XML10_ENTITY_REF = '&' + XML10_NAME + ';';
var XML10_REFERENCE = XML10_ENTITY_REF + '|' + XML_CHAR_REF;
var XML10_ATT_VALUE = '"(([^<&"]|' + XML10_REFERENCE + ')*)"|' +
                      "'(([^<&']|" + XML10_REFERENCE + ")*)'";
var XML10_ATTRIBUTE =
  '(' + XML10_NAME + ')' + XML_EQ + '(' + XML10_ATT_VALUE + ')';








var XML11_VERSION_INFO = XML_S + 'version' + XML_EQ + '("1\\.1"|' + "'1\\.1')";
var XML11_NAME_START_CHAR = (REGEXP_UNICODE) ?
  ':A-Z_a-z\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u02ff\u0370-\u037d' +
  '\u037f-\u1fff\u200c-\u200d\u2070-\u218f\u2c00-\u2fef\u3001-\ud7ff' +
  '\uf900-\ufdcf\ufdf0-\ufffd' :
  ':A-Z_a-z';
var XML11_NAME_CHAR = XML11_NAME_START_CHAR +
  ((REGEXP_UNICODE) ? '\\.0-9\u00b7\u0300-\u036f\u203f-\u2040-' : '\\.0-9-');
var XML11_NAME = '[' + XML11_NAME_START_CHAR + '][' + XML11_NAME_CHAR + ']*';

var XML11_ENTITY_REF = '&' + XML11_NAME + ';';
var XML11_REFERENCE = XML11_ENTITY_REF + '|' + XML_CHAR_REF;
var XML11_ATT_VALUE = '"(([^<&"]|' + XML11_REFERENCE + ')*)"|' +
                      "'(([^<&']|" + XML11_REFERENCE + ")*)'";
var XML11_ATTRIBUTE =
  '(' + XML11_NAME + ')' + XML_EQ + '(' + XML11_ATT_VALUE + ')';




var XML_NC_NAME_CHAR = XML10_LETTER + XML10_DIGIT + '\\._' +
                       XML10_COMBINING_CHAR + XML10_EXTENDER + '-';
var XML_NC_NAME = '[' + XML10_LETTER + '_][' + XML_NC_NAME_CHAR + ']*';

// end of ../ajaxslt/xmltoken.js

// begin ../ajaxslt/dom.js

















function xmlResolveEntities(s) {

  var parts = stringSplit(s, '&');

  var ret = parts[0];
  for (var i = 1; i < parts.length; ++i) {
    var rp = parts[i].indexOf(';');
    if (rp == -1) {
      
      ret += parts[i];
      continue;
    }

    var entityName = parts[i].substring(0, rp);
    var remainderText = parts[i].substring(rp + 1);

    var ch;
    switch (entityName) {
      case 'lt':
        ch = '<';
        break;
      case 'gt':
        ch = '>';
        break;
      case 'amp':
        ch = '&';
        break;
      case 'quot':
        ch = '"';
        break;
      case 'apos':
        ch = '\'';
        break;
      case 'nbsp':
        ch = String.fromCharCode(160);
        break;
      default:
        
        
        
        
        var span = domCreateElement(window.document, 'span');
        span.innerHTML = '&' + entityName + '; ';
        ch = span.childNodes[0].nodeValue.charAt(0);
    }
    ret += ch + remainderText;
  }

  return ret;
}

var XML10_TAGNAME_REGEXP = new RegExp('^(' + XML10_NAME + ')');
var XML10_ATTRIBUTE_REGEXP = new RegExp(XML10_ATTRIBUTE, 'g');

var XML11_TAGNAME_REGEXP = new RegExp('^(' + XML11_NAME + ')');
var XML11_ATTRIBUTE_REGEXP = new RegExp(XML11_ATTRIBUTE, 'g');



function xmlParse(xml) {
  var regex_empty = /\/$/;

  var regex_tagname;
  var regex_attribute;
  if (xml.match(/^<\?xml/)) {
    
    
    if (xml.search(new RegExp(XML10_VERSION_INFO)) == 5) {
      regex_tagname = XML10_TAGNAME_REGEXP;
      regex_attribute = XML10_ATTRIBUTE_REGEXP;
    } else if (xml.search(new RegExp(XML11_VERSION_INFO)) == 5) {
      regex_tagname = XML11_TAGNAME_REGEXP;
      regex_attribute = XML11_ATTRIBUTE_REGEXP;
    } else {
      
      
      alert('VersionInfo is missing, or unknown version number.');
    }
  } else {
    
    regex_tagname = XML10_TAGNAME_REGEXP;
    regex_attribute = XML10_ATTRIBUTE_REGEXP;
  }

  var xmldoc = new XDocument();
  var root = xmldoc;

  
  
  

  
  
  
  
  var stack = [];

  var parent = root;
  stack.push(parent);

  
  
  var slurp = '';

  var x = stringSplit(xml, '<');
  for (var i = 1; i < x.length; ++i) {
    var xx = stringSplit(x[i], '>');
    var tag = xx[0];
    var text = xmlResolveEntities(xx[1] || '');

    if (slurp) {
      
      
      var end = x[i].indexOf(slurp);
      if (end != -1) {
        var data = x[i].substring(0, end);
        parent.nodeValue += '<' + data;
        stack.pop();
        parent = stack[stack.length-1];
        text = x[i].substring(end + slurp.length);
        slurp = '';
      } else {
        parent.nodeValue += '<' + x[i];
        text = null;
      }

    } else if (tag.indexOf('![CDATA[') == 0) {
      var start = '![CDATA['.length;
      var end = x[i].indexOf(']]>');
      if (end != -1) {
        var data = x[i].substring(start, end);
        var node = domCreateCDATASection(xmldoc, data);
        domAppendChild(parent, node);
      } else {
        var data = x[i].substring(start);
        text = null;
        var node = domCreateCDATASection(xmldoc, data);
        domAppendChild(parent, node);
        parent = node;
        stack.push(node);
        slurp = ']]>';
      }

    } else if (tag.indexOf('!--') == 0) {
      var start = '!--'.length;
      var end = x[i].indexOf('-->');
      if (end != -1) {
        var data = x[i].substring(start, end);
        var node = domCreateComment(xmldoc, data);
        domAppendChild(parent, node);
      } else {
        var data = x[i].substring(start);
        text = null;
        var node = domCreateComment(xmldoc, data);
        domAppendChild(parent, node);
        parent = node;
        stack.push(node);
        slurp = '-->';
      }

    } else if (tag.charAt(0) == '/') {
      stack.pop();
      parent = stack[stack.length-1];

    } else if (tag.charAt(0) == '?') {
      
    } else if (tag.charAt(0) == '!') {
      
    } else {
      var empty = tag.match(regex_empty);
      var tagname = regex_tagname.exec(tag)[1];
      var node = domCreateElement(xmldoc, tagname);

      var att;
      while (att = regex_attribute.exec(tag)) {
        var val = xmlResolveEntities(att[5] || att[7] || '');
        domSetAttribute(node, att[1], val);
      }

      domAppendChild(parent, node);
      if (!empty) {
        parent = node;
        stack.push(node);
      }
    }

    if (text && parent != root) {
      domAppendChild(parent, domCreateTextNode(xmldoc, text));
    }
  }

  return root;
}



var DOM_ELEMENT_NODE = 1;
var DOM_ATTRIBUTE_NODE = 2;
var DOM_TEXT_NODE = 3;
var DOM_CDATA_SECTION_NODE = 4;
var DOM_ENTITY_REFERENCE_NODE = 5;
var DOM_ENTITY_NODE = 6;
var DOM_PROCESSING_INSTRUCTION_NODE = 7;
var DOM_COMMENT_NODE = 8;
var DOM_DOCUMENT_NODE = 9;
var DOM_DOCUMENT_TYPE_NODE = 10;
var DOM_DOCUMENT_FRAGMENT_NODE = 11;
var DOM_NOTATION_NODE = 12;








function domTraverseElements(node, opt_pre, opt_post) {
  var ret;
  if (opt_pre) {
    ret = opt_pre.call(null, node);
    if (typeof ret == 'boolean' && !ret) {
      return false;
    }
  }

  for (var c = node.firstChild; c; c = c.nextSibling) {
    if (c.nodeType == DOM_ELEMENT_NODE) {
      ret = arguments.callee.call(this, c, opt_pre, opt_post);
      if (typeof ret == 'boolean' && !ret) {
        return false;
      }
    }
  }

  if (opt_post) {
    ret = opt_post.call(null, node);
    if (typeof ret == 'boolean' && !ret) {
      return false;
    }
  }
}






function XNode(type, name, opt_value, opt_owner) {
  this.attributes = [];
  this.childNodes = [];
	this.childNodes.item = function(idx) {
		return this[idx];
	}

  XNode.init.call(this, type, name, opt_value, opt_owner);
}


XNode.init = function(type, name, value, owner) {
  this.nodeType = type - 0;
  this.nodeName = '' + name;
  this.nodeValue = '' + value;
  this.ownerDocument = owner;

  this.firstChild = null;
  this.lastChild = null;
  this.nextSibling = null;
  this.previousSibling = null;
  this.parentNode = null;
}

XNode.unused_ = [];

XNode.recycle = function(node) {
  if (!node) {
    return;
  }

  if (node.constructor == XDocument) {
    XNode.recycle(node.documentElement);
    return;
  }

  if (node.constructor != this) {
    return;
  }

  XNode.unused_.push(node);
  for (var a = 0; a < node.attributes.length; ++a) {
    XNode.recycle(node.attributes[a]);
  }
  for (var c = 0; c < node.childNodes.length; ++c) {
    XNode.recycle(node.childNodes[c]);
  }
  node.attributes.length = 0;
  node.childNodes.length = 0;
  XNode.init.call(node, 0, '', '', null);
}

XNode.create = function(type, name, value, owner) {
  if (XNode.unused_.length > 0) {
    var node = XNode.unused_.pop();
    XNode.init.call(node, type, name, value, owner);
    return node;
  } else {
    return new XNode(type, name, value, owner);
  }
}

XNode.prototype.appendChild = function(node) {
  
  if (this.childNodes.length == 0) {
    this.firstChild = node;
  }

  
  node.previousSibling = this.lastChild;

  
  node.nextSibling = null;
  if (this.lastChild) {
    this.lastChild.nextSibling = node;
  }

  
  node.parentNode = this;

  
  this.lastChild = node;

  
  this.childNodes.push(node);
}


XNode.prototype.replaceChild = function(newNode, oldNode) {
  if (oldNode == newNode) {
    return;
  }

  for (var i = 0; i < this.childNodes.length; ++i) {
    if (this.childNodes[i] == oldNode) {
      this.childNodes[i] = newNode;

      var p = oldNode.parentNode;
      oldNode.parentNode = null;
      newNode.parentNode = p;

      p = oldNode.previousSibling;
      oldNode.previousSibling = null;
      newNode.previousSibling = p;
      if (newNode.previousSibling) {
        newNode.previousSibling.nextSibling = newNode;
      }

      p = oldNode.nextSibling;
      oldNode.nextSibling = null;
      newNode.nextSibling = p;
      if (newNode.nextSibling) {
        newNode.nextSibling.previousSibling = newNode;
      }

      if (this.firstChild == oldNode) {
        this.firstChild = newNode;
      }

      if (this.lastChild == oldNode) {
        this.lastChild = newNode;
      }

      break;
    }
  }
}


XNode.prototype.insertBefore = function(newNode, oldNode) {
  if (oldNode == newNode) {
    return;
  }

  if (oldNode.parentNode != this) {
    return;
  }

  if (newNode.parentNode) {
    newNode.parentNode.removeChild(newNode);
  }

  var newChildren = [];
  for (var i = 0; i < this.childNodes.length; ++i) {
    var c = this.childNodes[i];
    if (c == oldNode) {
      newChildren.push(newNode);

      newNode.parentNode = this;

      newNode.previousSibling = oldNode.previousSibling;
      oldNode.previousSibling = newNode;
      if (newNode.previousSibling) {
        newNode.previousSibling.nextSibling = newNode;
      }

      newNode.nextSibling = oldNode;

      if (this.firstChild == oldNode) {
        this.firstChild = newNode;
      }
    }
    newChildren.push(c);
  }
  this.childNodes = newChildren;
}


XNode.prototype.removeChild = function(node) {
  var newChildren = [];
  for (var i = 0; i < this.childNodes.length; ++i) {
    var c = this.childNodes[i];
    if (c != node) {
      newChildren.push(c);
    } else {
      if (c.previousSibling) {
        c.previousSibling.nextSibling = c.nextSibling;
      }
      if (c.nextSibling) {
        c.nextSibling.previousSibling = c.previousSibling;
      }
      if (this.firstChild == c) {
        this.firstChild = c.nextSibling;
      }
      if (this.lastChild == c) {
        this.lastChild = c.previousSibling;
      }
    }
  }
  this.childNodes = newChildren;
}


XNode.prototype.hasAttributes = function() {
  return this.attributes.length > 0;
}


XNode.prototype.setAttribute = function(name, value) {
  for (var i = 0; i < this.attributes.length; ++i) {
    if (this.attributes[i].nodeName == name) {
      this.attributes[i].nodeValue = '' + value;
      return;
    }
  }
  this.attributes.push(XNode.create(DOM_ATTRIBUTE_NODE, name, value, this));
}


XNode.prototype.getAttribute = function(name) {
  for (var i = 0; i < this.attributes.length; ++i) {
    if (this.attributes[i].nodeName == name) {
      return this.attributes[i].nodeValue;
    }
  }
  return null;
}


XNode.prototype.removeAttribute = function(name) {
  var a = [];
  for (var i = 0; i < this.attributes.length; ++i) {
    if (this.attributes[i].nodeName != name) {
      a.push(this.attributes[i]);
    }
  }
  this.attributes = a;
}


XNode.prototype.getElementsByTagName = function(name) {
  var ret = [];
  domTraverseElements(this, function(node) {
    if (node.nodeName == name) {
      ret.push(node);
    }
  }, null);
  return ret;
}


XNode.prototype.getElementById = function(id) {
  var ret = null;
  domTraverseElements(this, function(node) {
    if (node.getAttribute('id') == id) {
      ret = node;
      return false;
    }
  }, null);
  return ret;
}


function XDocument() {
  
  
  XNode.call(this, DOM_DOCUMENT_NODE, '#document', null, null);
  this.documentElement = null;
}

XDocument.prototype = new XNode(DOM_DOCUMENT_NODE, '#document');

XDocument.prototype.clear = function() {
  XNode.recycle(this.documentElement);
  this.documentElement = null;
}

XDocument.prototype.appendChild = function(node) {
  XNode.prototype.appendChild.call(this, node);
  this.documentElement = this.childNodes[0];
}

XDocument.prototype.createElement = function(name) {
  return XNode.create(DOM_ELEMENT_NODE, name, null, this);
}

XDocument.prototype.createDocumentFragment = function() {
  return XNode.create(DOM_DOCUMENT_FRAGMENT_NODE, '#document-fragment',
                    null, this);
}

XDocument.prototype.createTextNode = function(value) {
  
  var node = XNode.create(DOM_TEXT_NODE, '#text', value, this);
  if(value == null) value='';
  node.data = value;
  node.length = value.length;
  return node;
}

XDocument.prototype.createAttribute = function(name) {
  return XNode.create(DOM_ATTRIBUTE_NODE, name, null, this);
}

XDocument.prototype.createComment = function(data) {
  return XNode.create(DOM_COMMENT_NODE, '#comment', data, this);
}

XDocument.prototype.createCDATASection = function(data) {
  
  var node = XNode.create(DOM_CDATA_SECTION_NODE, '#cdata-section', data, this);
  if(data==null) data='';
  node.data = data;
  node.length = data.length;
  return node;
}

// end of ../ajaxslt/dom.js

// begin ../ajaxslt/xpath.js










































function xpathParse(expr) {
  xpathLog('parse ' + expr);
  xpathParseInit();

  var cached = xpathCacheLookup(expr);
  if (cached) {
    xpathLog(' ... cached');
    return cached;
  }

  
  
  
  
  

  if (expr.match(/^(\$|@)?\w+$/i)) {
    var ret = makeSimpleExpr(expr);
    xpathParseCache[expr] = ret;
    xpathLog(' ... simple');
    return ret;
  }

  if (expr.match(/^\w+(\/\w+)*$/i)) {
    var ret = makeSimpleExpr2(expr);
    xpathParseCache[expr] = ret;
    xpathLog(' ... simple 2');
    return ret;
  }

  var cachekey = expr; 

  var stack = [];
  var ahead = null;
  var previous = null;
  var done = false;

  var parse_count = 0;
  var lexer_count = 0;
  var reduce_count = 0;

  while (!done) {
    parse_count++;
    expr = expr.replace(/^\s*/, '');
    previous = ahead;
    ahead = null;

    var rule = null;
    var match = '';
    for (var i = 0; i < xpathTokenRules.length; ++i) {
      var result = xpathTokenRules[i].re.exec(expr);
      lexer_count++;
      if (result && result.length > 0 && result[0].length > match.length) {
        rule = xpathTokenRules[i];
        match = result[0];
        break;
      }
    }

    
    

    
    
    
    
    
    
    
    
    
    

    if (rule &&
        (rule == TOK_DIV ||
         rule == TOK_MOD ||
         rule == TOK_AND ||
         rule == TOK_OR) &&
        (!previous ||
         previous.tag == TOK_AT ||
         previous.tag == TOK_DSLASH ||
         previous.tag == TOK_SLASH ||
         previous.tag == TOK_AXIS ||
         previous.tag == TOK_DOLLAR)) {
      rule = TOK_QNAME;
    }

    if (rule) {
      expr = expr.substr(match.length);
      xpathLog('token: ' + match + ' -- ' + rule.label);
      ahead = {
        tag: rule,
        match: match,
        prec: rule.prec ?  rule.prec : 0, 
        expr: makeTokenExpr(match)
      };

    } else {
      xpathLog('DONE');
      done = true;
    }

    while (xpathReduce(stack, ahead)) {
      reduce_count++;
      xpathLog('stack: ' + stackToString(stack));
    }
  }

  xpathLog('stack: ' + stackToString(stack));

  if (stack.length != 1) {
    throw 'XPath parse error ' + cachekey + ':\n' + stackToString(stack);
  }

  var result = stack[0].expr;
  xpathParseCache[cachekey] = result;

  xpathLog('XPath parse: ' + parse_count + ' / ' +
           lexer_count + ' / ' + reduce_count);

  return result;
}

var xpathParseCache = {};

function xpathCacheLookup(expr) {
  return xpathParseCache[expr];
}

function xpathReduce(stack, ahead) {
  var cand = null;

  if (stack.length > 0) {
    var top = stack[stack.length-1];
    var ruleset = xpathRules[top.tag.key];

    if (ruleset) {
      for (var i = 0; i < ruleset.length; ++i) {
        var rule = ruleset[i];
        var match = xpathMatchStack(stack, rule[1]);
        if (match.length) {
          cand = {
            tag: rule[0],
            rule: rule,
            match: match
          };
          cand.prec = xpathGrammarPrecedence(cand);
          break;
        }
      }
    }
  }

  var ret;
  if (cand && (!ahead || cand.prec > ahead.prec ||
               (ahead.tag.left && cand.prec >= ahead.prec))) {
    for (var i = 0; i < cand.match.matchlength; ++i) {
      stack.pop();
    }

    xpathLog('reduce ' + cand.tag.label + ' ' + cand.prec +
             ' ahead ' + (ahead ? ahead.tag.label + ' ' + ahead.prec +
                          (ahead.tag.left ? ' left' : '')
                          : ' none '));

    var matchexpr = mapExpr(cand.match, function(m) { return m.expr; });
    cand.expr = cand.rule[3].apply(null, matchexpr);

    stack.push(cand);
    ret = true;

  } else {
    if (ahead) {
      xpathLog('shift ' + ahead.tag.label + ' ' + ahead.prec +
               (ahead.tag.left ? ' left' : '') +
               ' over ' + (cand ? cand.tag.label + ' ' +
                           cand.prec : ' none'));
      stack.push(ahead);
    }
    ret = false;
  }
  return ret;
}

function xpathMatchStack(stack, pattern) {

  
  
  
  
  
  

  var S = stack.length;
  var P = pattern.length;
  var p, s;
  var match = [];
  match.matchlength = 0;
  var ds = 0;
  for (p = P - 1, s = S - 1; p >= 0 && s >= 0; --p, s -= ds) {
    ds = 0;
    var qmatch = [];
    if (pattern[p] == Q_MM) {
      p -= 1;
      match.push(qmatch);
      while (s - ds >= 0 && stack[s - ds].tag == pattern[p]) {
        qmatch.push(stack[s - ds]);
        ds += 1;
        match.matchlength += 1;
      }

    } else if (pattern[p] == Q_01) {
      p -= 1;
      match.push(qmatch);
      while (s - ds >= 0 && ds < 2 && stack[s - ds].tag == pattern[p]) {
        qmatch.push(stack[s - ds]);
        ds += 1;
        match.matchlength += 1;
      }

    } else if (pattern[p] == Q_1M) {
      p -= 1;
      match.push(qmatch);
      if (stack[s].tag == pattern[p]) {
        while (s - ds >= 0 && stack[s - ds].tag == pattern[p]) {
          qmatch.push(stack[s - ds]);
          ds += 1;
          match.matchlength += 1;
        }
      } else {
        return [];
      }

    } else if (stack[s].tag == pattern[p]) {
      match.push(stack[s]);
      ds += 1;
      match.matchlength += 1;

    } else {
      return [];
    }

    reverseInplace(qmatch);
    qmatch.expr = mapExpr(qmatch, function(m) { return m.expr; });
  }

  reverseInplace(match);

  if (p == -1) {
    return match;

  } else {
    return [];
  }
}

function xpathTokenPrecedence(tag) {
  return tag.prec || 2;
}

function xpathGrammarPrecedence(frame) {
  var ret = 0;

  if (frame.rule) {  
    if (frame.rule.length >= 3 && frame.rule[2] >= 0) {
      ret = frame.rule[2];

    } else {
      for (var i = 0; i < frame.rule[1].length; ++i) {
        var p = xpathTokenPrecedence(frame.rule[1][i]);
        ret = Math.max(ret, p);
      }
    }
  } else if (frame.tag) {  
    ret = xpathTokenPrecedence(frame.tag);

  } else if (frame.length) {  
    for (var j = 0; j < frame.length; ++j) {
      var p = xpathGrammarPrecedence(frame[j]);
      ret = Math.max(ret, p);
    }
  }

  return ret;
}

function stackToString(stack) {
  var ret = '';
  for (var i = 0; i < stack.length; ++i) {
    if (ret) {
      ret += '\n';
    }
    ret += stack[i].tag.label;
  }
  return ret;
}



































function ExprContext(node, opt_position, opt_nodelist, opt_parent) {
  this.node = node;
  this.position = opt_position || 0;
  this.nodelist = opt_nodelist || [ node ];
  this.variables = {};
  this.parent = opt_parent || null;
  if (opt_parent) {
    this.root = opt_parent.root;
  } else if (this.node.nodeType == DOM_DOCUMENT_NODE) {
    
    
    
    
    this.root = node;
  } else {
    this.root = node.ownerDocument;
  }
}

ExprContext.prototype.clone = function(opt_node, opt_position, opt_nodelist) {
  return new ExprContext(
      opt_node || this.node,
      typeof opt_position != 'undefined' ? opt_position : this.position,
      opt_nodelist || this.nodelist, this);
};

ExprContext.prototype.setVariable = function(name, value) {
  this.variables[name] = value;
};

ExprContext.prototype.getVariable = function(name) {
  if (typeof this.variables[name] != 'undefined') {
    return this.variables[name];

  } else if (this.parent) {
    return this.parent.getVariable(name);

  } else {
    return null;
  }
};

ExprContext.prototype.setNode = function(position) {
  this.node = this.nodelist[position];
  this.position = position;
};

ExprContext.prototype.contextSize = function() {
  return this.nodelist.length;
};



































function StringValue(value) {
  this.value = value;
  this.type = 'string';
}

StringValue.prototype.stringValue = function() {
  return this.value;
}

StringValue.prototype.booleanValue = function() {
  return this.value.length > 0;
}

StringValue.prototype.numberValue = function() {
  return this.value - 0;
}

StringValue.prototype.nodeSetValue = function() {
  throw this;
}

function BooleanValue(value) {
  this.value = value;
  this.type = 'boolean';
}

BooleanValue.prototype.stringValue = function() {
  return '' + this.value;
}

BooleanValue.prototype.booleanValue = function() {
  return this.value;
}

BooleanValue.prototype.numberValue = function() {
  return this.value ? 1 : 0;
}

BooleanValue.prototype.nodeSetValue = function() {
  throw this;
}

function NumberValue(value) {
  this.value = value;
  this.type = 'number';
}

NumberValue.prototype.stringValue = function() {
  return '' + this.value;
}

NumberValue.prototype.booleanValue = function() {
  return !!this.value;
}

NumberValue.prototype.numberValue = function() {
  return this.value - 0;
}

NumberValue.prototype.nodeSetValue = function() {
  throw this;
}

function NodeSetValue(value) {
  this.value = value;
  this.type = 'node-set';
}

NodeSetValue.prototype.stringValue = function() {
  if (this.value.length == 0) {
    return '';
  } else {
    return xmlValue(this.value[0]);
  }
}

NodeSetValue.prototype.booleanValue = function() {
  return this.value.length > 0;
}

NodeSetValue.prototype.numberValue = function() {
  return this.stringValue() - 0;
}

NodeSetValue.prototype.nodeSetValue = function() {
  return this.value;
};


















function TokenExpr(m) {
  this.value = m;
}

TokenExpr.prototype.evaluate = function() {
  return new StringValue(this.value);
};

function LocationExpr() {
  this.absolute = false;
  this.steps = [];
}

LocationExpr.prototype.appendStep = function(s) {
  this.steps.push(s);
}

LocationExpr.prototype.prependStep = function(s) {
  var steps0 = this.steps;
  this.steps = [ s ];
  for (var i = 0; i < steps0.length; ++i) {
    this.steps.push(steps0[i]);
  }
};

LocationExpr.prototype.evaluate = function(ctx) {
  var start;
  if (this.absolute) {
    start = ctx.root;

  } else {
    start = ctx.node;
  }

  var nodes = [];
  xPathStep(nodes, this.steps, 0, start, ctx);
  return new NodeSetValue(nodes);
};

function xPathStep(nodes, steps, step, input, ctx) {
  var s = steps[step];
  var ctx2 = ctx.clone(input);
  var nodelist = s.evaluate(ctx2).nodeSetValue();

  for (var i = 0; i < nodelist.length; ++i) {
    if (step == steps.length - 1) {
      nodes.push(nodelist[i]);
    } else {
      xPathStep(nodes, steps, step + 1, nodelist[i], ctx);
    }
  }
}

function StepExpr(axis, nodetest, opt_predicate) {
  this.axis = axis;
  this.nodetest = nodetest;
  this.predicate = opt_predicate || [];
}

StepExpr.prototype.appendPredicate = function(p) {
  this.predicate.push(p);
}

StepExpr.prototype.evaluate = function(ctx) {
  var input = ctx.node;
  var nodelist = [];

  
  
  

  if (this.axis ==  xpathAxis.ANCESTOR_OR_SELF) {
    nodelist.push(input);
    for (var n = input.parentNode; n; n = n.parentNode) {
      nodelist.push(n);
    }

  } else if (this.axis == xpathAxis.ANCESTOR) {
    for (var n = input.parentNode; n; n = n.parentNode) {
      nodelist.push(n);
    }

  } else if (this.axis == xpathAxis.ATTRIBUTE) {
    copyArray(nodelist, input.attributes);

  } else if (this.axis == xpathAxis.CHILD) {
    copyArray(nodelist, input.childNodes);

  } else if (this.axis == xpathAxis.DESCENDANT_OR_SELF) {
    nodelist.push(input);
    xpathCollectDescendants(nodelist, input);

  } else if (this.axis == xpathAxis.DESCENDANT) {
    xpathCollectDescendants(nodelist, input);

  } else if (this.axis == xpathAxis.FOLLOWING) {
    for (var n = input; n; n = n.parentNode) {
      for (var nn = n.nextSibling; nn; nn = nn.nextSibling) {
        nodelist.push(nn);
        xpathCollectDescendants(nodelist, nn);
      }
    }

  } else if (this.axis == xpathAxis.FOLLOWING_SIBLING) {
    for (var n = input.nextSibling; n; n = n.nextSibling) {
      nodelist.push(n);
    }

  } else if (this.axis == xpathAxis.NAMESPACE) {
    alert('not implemented: axis namespace');

  } else if (this.axis == xpathAxis.PARENT) {
    if (input.parentNode) {
      nodelist.push(input.parentNode);
    }

  } else if (this.axis == xpathAxis.PRECEDING) {
    for (var n = input; n; n = n.parentNode) {
      for (var nn = n.previousSibling; nn; nn = nn.previousSibling) {
        nodelist.push(nn);
        xpathCollectDescendantsReverse(nodelist, nn);
      }
    }

  } else if (this.axis == xpathAxis.PRECEDING_SIBLING) {
    for (var n = input.previousSibling; n; n = n.previousSibling) {
      nodelist.push(n);
    }

  } else if (this.axis == xpathAxis.SELF) {
    nodelist.push(input);

  } else {
    throw 'ERROR -- NO SUCH AXIS: ' + this.axis;
  }

  
  var nodelist0 = nodelist;
  nodelist = [];
  for (var i = 0; i < nodelist0.length; ++i) {
    var n = nodelist0[i];
    if (this.nodetest.evaluate(ctx.clone(n, i, nodelist0)).booleanValue()) {
      nodelist.push(n);
    }
  }

  
  for (var i = 0; i < this.predicate.length; ++i) {
    var nodelist0 = nodelist;
    nodelist = [];
    for (var ii = 0; ii < nodelist0.length; ++ii) {
      var n = nodelist0[ii];
      if (this.predicate[i].evaluate(ctx.clone(n, ii, nodelist0)).booleanValue()) {
        nodelist.push(n);
      }
    }
  }

  return new NodeSetValue(nodelist);
};

function NodeTestAny() {
  this.value = new BooleanValue(true);
}

NodeTestAny.prototype.evaluate = function(ctx) {
  return this.value;
};

function NodeTestElementOrAttribute() {}

NodeTestElementOrAttribute.prototype.evaluate = function(ctx) {
  return new BooleanValue(
      ctx.node.nodeType == DOM_ELEMENT_NODE ||
      ctx.node.nodeType == DOM_ATTRIBUTE_NODE);
}

function NodeTestText() {}

NodeTestText.prototype.evaluate = function(ctx) {
  return new BooleanValue(ctx.node.nodeType == DOM_TEXT_NODE);
}

function NodeTestComment() {}

NodeTestComment.prototype.evaluate = function(ctx) {
  return new BooleanValue(ctx.node.nodeType == DOM_COMMENT_NODE);
}

function NodeTestPI(target) {
  this.target = target;
}

NodeTestPI.prototype.evaluate = function(ctx) {
  return new
  BooleanValue(ctx.node.nodeType == DOM_PROCESSING_INSTRUCTION_NODE &&
               (!this.target || ctx.node.nodeName == this.target));
}

function NodeTestNC(nsprefix) {
  this.regex = new RegExp("^" + nsprefix + ":");
  this.nsprefix = nsprefix;
}

NodeTestNC.prototype.evaluate = function(ctx) {
  var n = ctx.node;
  return new BooleanValue(this.regex.match(n.nodeName));
}

function NodeTestName(name) {
  this.name = name;
}

NodeTestName.prototype.evaluate = function(ctx) {
  var n = ctx.node;
  return new BooleanValue(n.nodeName == this.name);
}

function PredicateExpr(expr) {
  this.expr = expr;
}

PredicateExpr.prototype.evaluate = function(ctx) {
  var v = this.expr.evaluate(ctx);
  if (v.type == 'number') {
    
    
    
    return new BooleanValue(ctx.position == v.numberValue() - 1);
  } else {
    return new BooleanValue(v.booleanValue());
  }
};

function FunctionCallExpr(name) {
  this.name = name;
  this.args = [];
}

FunctionCallExpr.prototype.appendArg = function(arg) {
  this.args.push(arg);
};

FunctionCallExpr.prototype.evaluate = function(ctx) {
  var fn = '' + this.name.value;
  var f = this.xpathfunctions[fn];
  if (f) {
    return f.call(this, ctx);
  } else {
    xpathLog('XPath NO SUCH FUNCTION ' + fn);
    return new BooleanValue(false);
  }
};

FunctionCallExpr.prototype.xpathfunctions = {
  'last': function(ctx) {
    assert(this.args.length == 0);
    
    return new NumberValue(ctx.contextSize());
  },

  'position': function(ctx) {
    assert(this.args.length == 0);
    
    return new NumberValue(ctx.position + 1);
  },

  'count': function(ctx) {
    assert(this.args.length == 1);
    var v = this.args[0].evaluate(ctx);
    return new NumberValue(v.nodeSetValue().length);
  },

  'id': function(ctx) {
    assert(this.args.length == 1);
    var e = this.args[0].evaluate(ctx);
    var ret = [];
    var ids;
    if (e.type == 'node-set') {
      ids = [];
      var en = e.nodeSetValue();
      for (var i = 0; i < en.length; ++i) {
        var v = xmlValue(en[i]).split(/\s+/);
        for (var ii = 0; ii < v.length; ++ii) {
          ids.push(v[ii]);
        }
      }
    } else {
      ids = e.stringValue().split(/\s+/);
    }
    var d = ctx.node.ownerDocument;
    for (var i = 0; i < ids.length; ++i) {
      var n = d.getElementById(ids[i]);
      if (n) {
        ret.push(n);
      }
    }
    return new NodeSetValue(ret);
  },

  'local-name': function(ctx) {
    alert('not implmented yet: XPath function local-name()');
  },

  'namespace-uri': function(ctx) {
    alert('not implmented yet: XPath function namespace-uri()');
  },

  'name': function(ctx) {
    assert(this.args.length == 1 || this.args.length == 0);
    var n;
    if (this.args.length == 0) {
      n = [ ctx.node ];
    } else {
      n = this.args[0].evaluate(ctx).nodeSetValue();
    }

    if (n.length == 0) {
      return new StringValue('');
    } else {
      return new StringValue(n[0].nodeName);
    }
  },

  'string':  function(ctx) {
    assert(this.args.length == 1 || this.args.length == 0);
    if (this.args.length == 0) {
      return new StringValue(new NodeSetValue([ ctx.node ]).stringValue());
    } else {
      return new StringValue(this.args[0].evaluate(ctx).stringValue());
    }
  },

  'concat': function(ctx) {
    var ret = '';
    for (var i = 0; i < this.args.length; ++i) {
      ret += this.args[i].evaluate(ctx).stringValue();
    }
    return new StringValue(ret);
  },

  'starts-with': function(ctx) {
    assert(this.args.length == 2);
    var s0 = this.args[0].evaluate(ctx).stringValue();
    var s1 = this.args[1].evaluate(ctx).stringValue();
    return new BooleanValue(s0.indexOf(s1) == 0);
  },

  'contains': function(ctx) {
    assert(this.args.length == 2);
    var s0 = this.args[0].evaluate(ctx).stringValue();
    var s1 = this.args[1].evaluate(ctx).stringValue();
    return new BooleanValue(s0.indexOf(s1) != -1);
  },

  'substring-before': function(ctx) {
    assert(this.args.length == 2);
    var s0 = this.args[0].evaluate(ctx).stringValue();
    var s1 = this.args[1].evaluate(ctx).stringValue();
    var i = s0.indexOf(s1);
    var ret;
    if (i == -1) {
      ret = '';
    } else {
      ret = s0.substr(0,i);
    }
    return new StringValue(ret);
  },

  'substring-after': function(ctx) {
    assert(this.args.length == 2);
    var s0 = this.args[0].evaluate(ctx).stringValue();
    var s1 = this.args[1].evaluate(ctx).stringValue();
    var i = s0.indexOf(s1);
    var ret;
    if (i == -1) {
      ret = '';
    } else {
      ret = s0.substr(i + s1.length);
    }
    return new StringValue(ret);
  },

  'substring': function(ctx) {
    
    
    assert(this.args.length == 2 || this.args.length == 3);
    var s0 = this.args[0].evaluate(ctx).stringValue();
    var s1 = this.args[1].evaluate(ctx).numberValue();
    var ret;
    if (this.args.length == 2) {
      var i1 = Math.max(0, Math.round(s1) - 1);
      ret = s0.substr(i1);

    } else {
      var s2 = this.args[2].evaluate(ctx).numberValue();
      var i0 = Math.round(s1) - 1;
      var i1 = Math.max(0, i0);
      var i2 = Math.round(s2) - Math.max(0, -i0);
      ret = s0.substr(i1, i2);
    }
    return new StringValue(ret);
  },

  'string-length': function(ctx) {
    var s;
    if (this.args.length > 0) {
      s = this.args[0].evaluate(ctx).stringValue();
    } else {
      s = new NodeSetValue([ ctx.node ]).stringValue();
    }
    return new NumberValue(s.length);
  },

  'normalize-space': function(ctx) {
    var s;
    if (this.args.length > 0) {
      s = this.args[0].evaluate(ctx).stringValue();
    } else {
      s = new NodeSetValue([ ctx.node ]).stringValue();
    }
    s = s.replace(/^\s*/,'').replace(/\s*$/,'').replace(/\s+/g, ' ');
    return new StringValue(s);
  },

  'translate': function(ctx) {
    assert(this.args.length == 3);
    var s0 = this.args[0].evaluate(ctx).stringValue();
    var s1 = this.args[1].evaluate(ctx).stringValue();
    var s2 = this.args[2].evaluate(ctx).stringValue();

    for (var i = 0; i < s1.length; ++i) {
      s0 = s0.replace(new RegExp(s1.charAt(i), 'g'), s2.charAt(i));
    }
    return new StringValue(s0);
  },

  'boolean': function(ctx) {
    assert(this.args.length == 1);
    return new BooleanValue(this.args[0].evaluate(ctx).booleanValue());
  },

  'not': function(ctx) {
    assert(this.args.length == 1);
    var ret = !this.args[0].evaluate(ctx).booleanValue();
    return new BooleanValue(ret);
  },

  'true': function(ctx) {
    assert(this.args.length == 0);
    return new BooleanValue(true);
  },

  'false': function(ctx) {
    assert(this.args.length == 0);
    return new BooleanValue(false);
  },

  'lang': function(ctx) {
    assert(this.args.length == 1);
    var lang = this.args[0].evaluate(ctx).stringValue();
    var xmllang;
    var n = ctx.node;
    while (n && n != n.parentNode  ) {
      xmllang = n.getAttribute('xml:lang');
      if (xmllang) {
        break;
      }
      n = n.parentNode;
    }
    if (!xmllang) {
      return new BooleanValue(false);
    } else {
      var re = new RegExp('^' + lang + '$', 'i');
      return new BooleanValue(xmllang.match(re) ||
                              xmllang.replace(/_.*$/,'').match(re));
    }
  },

  'number': function(ctx) {
    assert(this.args.length == 1 || this.args.length == 0);

    if (this.args.length == 1) {
      return new NumberValue(this.args[0].evaluate(ctx).numberValue());
    } else {
      return new NumberValue(new NodeSetValue([ ctx.node ]).numberValue());
    }
  },

  'sum': function(ctx) {
    assert(this.args.length == 1);
    var n = this.args[0].evaluate(ctx).nodeSetValue();
    var sum = 0;
    for (var i = 0; i < n.length; ++i) {
      sum += xmlValue(n[i]) - 0;
    }
    return new NumberValue(sum);
  },

  'floor': function(ctx) {
    assert(this.args.length == 1);
    var num = this.args[0].evaluate(ctx).numberValue();
    return new NumberValue(Math.floor(num));
  },

  'ceiling': function(ctx) {
    assert(this.args.length == 1);
    var num = this.args[0].evaluate(ctx).numberValue();
    return new NumberValue(Math.ceil(num));
  },

  'round': function(ctx) {
    assert(this.args.length == 1);
    var num = this.args[0].evaluate(ctx).numberValue();
    return new NumberValue(Math.round(num));
  },

  
  
  

  'ext-join': function(ctx) {
    assert(this.args.length == 2);
    var nodes = this.args[0].evaluate(ctx).nodeSetValue();
    var delim = this.args[1].evaluate(ctx).stringValue();
    var ret = '';
    for (var i = 0; i < nodes.length; ++i) {
      if (ret) {
        ret += delim;
      }
      ret += xmlValue(nodes[i]);
    }
    return new StringValue(ret);
  },

  
  
  

  'ext-if': function(ctx) {
    assert(this.args.length == 3);
    if (this.args[0].evaluate(ctx).booleanValue()) {
      return this.args[1].evaluate(ctx);
    } else {
      return this.args[2].evaluate(ctx);
    }
  },

  
  
  

  'ext-cardinal': function(ctx) {
    assert(this.args.length >= 1);
    var c = this.args[0].evaluate(ctx).numberValue();
    var ret = [];
    for (var i = 0; i < c; ++i) {
      ret.push(ctx.node);
    }
    return new NodeSetValue(ret);
  }
};

function UnionExpr(expr1, expr2) {
  this.expr1 = expr1;
  this.expr2 = expr2;
}

UnionExpr.prototype.evaluate = function(ctx) {
  var nodes1 = this.expr1.evaluate(ctx).nodeSetValue();
  var nodes2 = this.expr2.evaluate(ctx).nodeSetValue();
  var I1 = nodes1.length;
  for (var i2 = 0; i2 < nodes2.length; ++i2) {
    var n = nodes2[i2];
    var inBoth = false;
    for (var i1 = 0; i1 < I1; ++i1) {
      if (nodes1[i1] == n) {
        inBoth = true;
        i1 = I1; 
      }
    }
    if (!inBoth) {
      nodes1.push(n);
    }
  }
  return new NodeSetValue(nodes1);
};

function PathExpr(filter, rel) {
  this.filter = filter;
  this.rel = rel;
}

PathExpr.prototype.evaluate = function(ctx) {
  var nodes = this.filter.evaluate(ctx).nodeSetValue();
  var nodes1 = [];
  for (var i = 0; i < nodes.length; ++i) {
    var nodes0 = this.rel.evaluate(ctx.clone(nodes[i], i, nodes)).nodeSetValue();
    for (var ii = 0; ii < nodes0.length; ++ii) {
      nodes1.push(nodes0[ii]);
    }
  }
  return new NodeSetValue(nodes1);
};

function FilterExpr(expr, predicate) {
  this.expr = expr;
  this.predicate = predicate;
}

FilterExpr.prototype.evaluate = function(ctx) {
  var nodes = this.expr.evaluate(ctx).nodeSetValue();
  for (var i = 0; i < this.predicate.length; ++i) {
    var nodes0 = nodes;
    nodes = [];
    for (var j = 0; j < nodes0.length; ++j) {
      var n = nodes0[j];
      if (this.predicate[i].evaluate(ctx.clone(n, j, nodes0)).booleanValue()) {
        nodes.push(n);
      }
    }
  }

  return new NodeSetValue(nodes);
}

function UnaryMinusExpr(expr) {
  this.expr = expr;
}

UnaryMinusExpr.prototype.evaluate = function(ctx) {
  return new NumberValue(-this.expr.evaluate(ctx).numberValue());
};

function BinaryExpr(expr1, op, expr2) {
  this.expr1 = expr1;
  this.expr2 = expr2;
  this.op = op;
}

BinaryExpr.prototype.evaluate = function(ctx) {
  var ret;
  switch (this.op.value) {
    case 'or':
      ret = new BooleanValue(this.expr1.evaluate(ctx).booleanValue() ||
                             this.expr2.evaluate(ctx).booleanValue());
      break;

    case 'and':
      ret = new BooleanValue(this.expr1.evaluate(ctx).booleanValue() &&
                             this.expr2.evaluate(ctx).booleanValue());
      break;

    case '+':
      ret = new NumberValue(this.expr1.evaluate(ctx).numberValue() +
                            this.expr2.evaluate(ctx).numberValue());
      break;

    case '-':
      ret = new NumberValue(this.expr1.evaluate(ctx).numberValue() -
                            this.expr2.evaluate(ctx).numberValue());
      break;

    case '*':
      ret = new NumberValue(this.expr1.evaluate(ctx).numberValue() *
                            this.expr2.evaluate(ctx).numberValue());
      break;

    case 'mod':
      ret = new NumberValue(this.expr1.evaluate(ctx).numberValue() %
                            this.expr2.evaluate(ctx).numberValue());
      break;

    case 'div':
      ret = new NumberValue(this.expr1.evaluate(ctx).numberValue() /
                            this.expr2.evaluate(ctx).numberValue());
      break;

    case '=':
      ret = this.compare(ctx, function(x1, x2) { return x1 == x2; });
      break;

    case '!=':
      ret = this.compare(ctx, function(x1, x2) { return x1 != x2; });
      break;

    case '<':
      ret = this.compare(ctx, function(x1, x2) { return x1 < x2; });
      break;

    case '<=':
      ret = this.compare(ctx, function(x1, x2) { return x1 <= x2; });
      break;

    case '>':
      ret = this.compare(ctx, function(x1, x2) { return x1 > x2; });
      break;

    case '>=':
      ret = this.compare(ctx, function(x1, x2) { return x1 >= x2; });
      break;

    default:
      alert('BinaryExpr.evaluate: ' + this.op.value);
  }
  return ret;
};

BinaryExpr.prototype.compare = function(ctx, cmp) {
  var v1 = this.expr1.evaluate(ctx);
  var v2 = this.expr2.evaluate(ctx);

  var ret;
  if (v1.type == 'node-set' && v2.type == 'node-set') {
    var n1 = v1.nodeSetValue();
    var n2 = v2.nodeSetValue();
    ret = false;
    for (var i1 = 0; i1 < n1.length; ++i1) {
      for (var i2 = 0; i2 < n2.length; ++i2) {
        if (cmp(xmlValue(n1[i1]), xmlValue(n2[i2]))) {
          ret = true;
          
          
          i2 = n2.length;
          i1 = n1.length;
        }
      }
    }

  } else if (v1.type == 'node-set' || v2.type == 'node-set') {

    if (v1.type == 'number') {
      var s = v1.numberValue();
      var n = v2.nodeSetValue();

      ret = false;
      for (var i = 0;  i < n.length; ++i) {
        var nn = xmlValue(n[i]) - 0;
        if (cmp(s, nn)) {
          ret = true;
          break;
        }
      }

    } else if (v2.type == 'number') {
      var n = v1.nodeSetValue();
      var s = v2.numberValue();

      ret = false;
      for (var i = 0;  i < n.length; ++i) {
        var nn = xmlValue(n[i]) - 0;
        if (cmp(nn, s)) {
          ret = true;
          break;
        }
      }

    } else if (v1.type == 'string') {
      var s = v1.stringValue();
      var n = v2.nodeSetValue();

      ret = false;
      for (var i = 0;  i < n.length; ++i) {
        var nn = xmlValue(n[i]);
        if (cmp(s, nn)) {
          ret = true;
          break;
        }
      }

    } else if (v2.type == 'string') {
      var n = v1.nodeSetValue();
      var s = v2.stringValue();

      ret = false;
      for (var i = 0;  i < n.length; ++i) {
        var nn = xmlValue(n[i]);
        if (cmp(nn, s)) {
          ret = true;
          break;
        }
      }

    } else {
      ret = cmp(v1.booleanValue(), v2.booleanValue());
    }

  } else if (v1.type == 'boolean' || v2.type == 'boolean') {
    ret = cmp(v1.booleanValue(), v2.booleanValue());

  } else if (v1.type == 'number' || v2.type == 'number') {
    ret = cmp(v1.numberValue(), v2.numberValue());

  } else {
    ret = cmp(v1.stringValue(), v2.stringValue());
  }

  return new BooleanValue(ret);
}

function LiteralExpr(value) {
  this.value = value;
}

LiteralExpr.prototype.evaluate = function(ctx) {
  return new StringValue(this.value);
};

function NumberExpr(value) {
  this.value = value;
}

NumberExpr.prototype.evaluate = function(ctx) {
  return new NumberValue(this.value);
};

function VariableExpr(name) {
  this.name = name;
}

VariableExpr.prototype.evaluate = function(ctx) {
  return ctx.getVariable(this.name);
}










function makeTokenExpr(m) {
  return new TokenExpr(m);
}

function passExpr(e) {
  return e;
}

function makeLocationExpr1(slash, rel) {
  rel.absolute = true;
  return rel;
}

function makeLocationExpr2(dslash, rel) {
  rel.absolute = true;
  rel.prependStep(makeAbbrevStep(dslash.value));
  return rel;
}

function makeLocationExpr3(slash) {
  var ret = new LocationExpr();
  ret.appendStep(makeAbbrevStep('.'));
  ret.absolute = true;
  return ret;
}

function makeLocationExpr4(dslash) {
  var ret = new LocationExpr();
  ret.absolute = true;
  ret.appendStep(makeAbbrevStep(dslash.value));
  return ret;
}

function makeLocationExpr5(step) {
  var ret = new LocationExpr();
  ret.appendStep(step);
  return ret;
}

function makeLocationExpr6(rel, slash, step) {
  rel.appendStep(step);
  return rel;
}

function makeLocationExpr7(rel, dslash, step) {
  rel.appendStep(makeAbbrevStep(dslash.value));
  return rel;
}

function makeStepExpr1(dot) {
  return makeAbbrevStep(dot.value);
}

function makeStepExpr2(ddot) {
  return makeAbbrevStep(ddot.value);
}

function makeStepExpr3(axisname, axis, nodetest) {
  return new StepExpr(axisname.value, nodetest);
}

function makeStepExpr4(at, nodetest) {
  return new StepExpr('attribute', nodetest);
}

function makeStepExpr5(nodetest) {
  return new StepExpr('child', nodetest);
}

function makeStepExpr6(step, predicate) {
  step.appendPredicate(predicate);
  return step;
}

function makeAbbrevStep(abbrev) {
  switch (abbrev) {
  case '//':
    return new StepExpr('descendant-or-self', new NodeTestAny);

  case '.':
    return new StepExpr('self', new NodeTestAny);

  case '..':
    return new StepExpr('parent', new NodeTestAny);
  }
}

function makeNodeTestExpr1(asterisk) {
  return new NodeTestElementOrAttribute;
}

function makeNodeTestExpr2(ncname, colon, asterisk) {
  return new NodeTestNC(ncname.value);
}

function makeNodeTestExpr3(qname) {
  return new NodeTestName(qname.value);
}

function makeNodeTestExpr4(typeo, parenc) {
  var type = typeo.value.replace(/\s*\($/, '');
  switch(type) {
  case 'node':
    return new NodeTestAny;

  case 'text':
    return new NodeTestText;

  case 'comment':
    return new NodeTestComment;

  case 'processing-instruction':
    return new NodeTestPI('');
  }
}

function makeNodeTestExpr5(typeo, target, parenc) {
  var type = typeo.replace(/\s*\($/, '');
  if (type != 'processing-instruction') {
    throw type;
  }
  return new NodeTestPI(target.value);
}

function makePredicateExpr(pareno, expr, parenc) {
  return new PredicateExpr(expr);
}

function makePrimaryExpr(pareno, expr, parenc) {
  return expr;
}

function makeFunctionCallExpr1(name, pareno, parenc) {
  return new FunctionCallExpr(name);
}

function makeFunctionCallExpr2(name, pareno, arg1, args, parenc) {
  var ret = new FunctionCallExpr(name);
  ret.appendArg(arg1);
  for (var i = 0; i < args.length; ++i) {
    ret.appendArg(args[i]);
  }
  return ret;
}

function makeArgumentExpr(comma, expr) {
  return expr;
}

function makeUnionExpr(expr1, pipe, expr2) {
  return new UnionExpr(expr1, expr2);
}

function makePathExpr1(filter, slash, rel) {
  return new PathExpr(filter, rel);
}

function makePathExpr2(filter, dslash, rel) {
  rel.prependStep(makeAbbrevStep(dslash.value));
  return new PathExpr(filter, rel);
}

function makeFilterExpr(expr, predicates) {
  if (predicates.length > 0) {
    return new FilterExpr(expr, predicates);
  } else {
    return expr;
  }
}

function makeUnaryMinusExpr(minus, expr) {
  return new UnaryMinusExpr(expr);
}

function makeBinaryExpr(expr1, op, expr2) {
  return new BinaryExpr(expr1, op, expr2);
}

function makeLiteralExpr(token) {
  
  var value = token.value.substring(1, token.value.length - 1);
  return new LiteralExpr(value);
}

function makeNumberExpr(token) {
  return new NumberExpr(token.value);
}

function makeVariableReference(dollar, name) {
  return new VariableExpr(name.value);
}



function makeSimpleExpr(expr) {
  if (expr.charAt(0) == '$') {
    return new VariableExpr(expr.substr(1));
  } else if (expr.charAt(0) == '@') {
    var a = new NodeTestName(expr.substr(1));
    var b = new StepExpr('attribute', a);
    var c = new LocationExpr();
    c.appendStep(b);
    return c;
  } else if (expr.match(/^[0-9]+$/)) {
    return new NumberExpr(expr);
  } else {
    var a = new NodeTestName(expr);
    var b = new StepExpr('child', a);
    var c = new LocationExpr();
    c.appendStep(b);
    return c;
  }
}

function makeSimpleExpr2(expr) {
  var steps = stringSplit(expr, '/');
  var c = new LocationExpr();
  for (var i = 0; i < steps.length; ++i) {
    var a = new NodeTestName(steps[i]);
    var b = new StepExpr('child', a);
    c.appendStep(b);
  }
  return c;
}



var xpathAxis = {
  ANCESTOR_OR_SELF: 'ancestor-or-self',
  ANCESTOR: 'ancestor',
  ATTRIBUTE: 'attribute',
  CHILD: 'child',
  DESCENDANT_OR_SELF: 'descendant-or-self',
  DESCENDANT: 'descendant',
  FOLLOWING_SIBLING: 'following-sibling',
  FOLLOWING: 'following',
  NAMESPACE: 'namespace',
  PARENT: 'parent',
  PRECEDING_SIBLING: 'preceding-sibling',
  PRECEDING: 'preceding',
  SELF: 'self'
};

var xpathAxesRe = [
    xpathAxis.ANCESTOR_OR_SELF,
    xpathAxis.ANCESTOR,
    xpathAxis.ATTRIBUTE,
    xpathAxis.CHILD,
    xpathAxis.DESCENDANT_OR_SELF,
    xpathAxis.DESCENDANT,
    xpathAxis.FOLLOWING_SIBLING,
    xpathAxis.FOLLOWING,
    xpathAxis.NAMESPACE,
    xpathAxis.PARENT,
    xpathAxis.PRECEDING_SIBLING,
    xpathAxis.PRECEDING,
    xpathAxis.SELF
].join('|');











var TOK_PIPE =   { label: "|",   prec:   17, re: new RegExp("^\\|") };
var TOK_DSLASH = { label: "//",  prec:   19, re: new RegExp("^//")  };
var TOK_SLASH =  { label: "/",   prec:   30, re: new RegExp("^/")   };
var TOK_AXIS =   { label: "::",  prec:   20, re: new RegExp("^::")  };
var TOK_COLON =  { label: ":",   prec: 1000, re: new RegExp("^:")  };
var TOK_AXISNAME = { label: "[axis]", re: new RegExp('^(' + xpathAxesRe + ')') };
var TOK_PARENO = { label: "(",   prec:   34, re: new RegExp("^\\(") };
var TOK_PARENC = { label: ")",               re: new RegExp("^\\)") };
var TOK_DDOT =   { label: "..",  prec:   34, re: new RegExp("^\\.\\.") };
var TOK_DOT =    { label: ".",   prec:   34, re: new RegExp("^\\.") };
var TOK_AT =     { label: "@",   prec:   34, re: new RegExp("^@")   };

var TOK_COMMA =  { label: ",",               re: new RegExp("^,") };

var TOK_OR =     { label: "or",  prec:   10, re: new RegExp("^or\\b") };
var TOK_AND =    { label: "and", prec:   11, re: new RegExp("^and\\b") };
var TOK_EQ =     { label: "=",   prec:   12, re: new RegExp("^=")   };
var TOK_NEQ =    { label: "!=",  prec:   12, re: new RegExp("^!=")  };
var TOK_GE =     { label: ">=",  prec:   13, re: new RegExp("^>=")  };
var TOK_GT =     { label: ">",   prec:   13, re: new RegExp("^>")   };
var TOK_LE =     { label: "<=",  prec:   13, re: new RegExp("^<=")  };
var TOK_LT =     { label: "<",   prec:   13, re: new RegExp("^<")   };
var TOK_PLUS =   { label: "+",   prec:   14, re: new RegExp("^\\+"), left: true };
var TOK_MINUS =  { label: "-",   prec:   14, re: new RegExp("^\\-"), left: true };
var TOK_DIV =    { label: "div", prec:   15, re: new RegExp("^div\\b"), left: true };
var TOK_MOD =    { label: "mod", prec:   15, re: new RegExp("^mod\\b"), left: true };

var TOK_BRACKO = { label: "[",   prec:   32, re: new RegExp("^\\[") };
var TOK_BRACKC = { label: "]",               re: new RegExp("^\\]") };
var TOK_DOLLAR = { label: "$",               re: new RegExp("^\\$") };

var TOK_NCNAME = { label: "[ncname]", re: new RegExp('^' + XML_NC_NAME) };

var TOK_ASTERISK = { label: "*", prec: 15, re: new RegExp("^\\*"), left: true };
var TOK_LITERALQ = { label: "[litq]", prec: 20, re: new RegExp("^'[^\\']*'") };
var TOK_LITERALQQ = {
  label: "[litqq]",
  prec: 20,
  re: new RegExp('^"[^\\"]*"')
};

var TOK_NUMBER  = {
  label: "[number]",
  prec: 35,
  re: new RegExp('^\\d+(\\.\\d*)?') };

var TOK_QNAME = {
  label: "[qname]",
  re: new RegExp('^(' + XML_NC_NAME + ':)?' + XML_NC_NAME)
};

var TOK_NODEO = {
  label: "[nodetest-start]",
  re: new RegExp('^(processing-instruction|comment|text|node)\\(')
};









var xpathTokenRules = [
    TOK_DSLASH,
    TOK_SLASH,
    TOK_DDOT,
    TOK_DOT,
    TOK_AXIS,
    TOK_COLON,
    TOK_AXISNAME,
    TOK_NODEO,
    TOK_PARENO,
    TOK_PARENC,
    TOK_BRACKO,
    TOK_BRACKC,
    TOK_AT,
    TOK_COMMA,
    TOK_OR,
    TOK_AND,
    TOK_NEQ,
    TOK_EQ,
    TOK_GE,
    TOK_GT,
    TOK_LE,
    TOK_LT,
    TOK_PLUS,
    TOK_MINUS,
    TOK_ASTERISK,
    TOK_PIPE,
    TOK_MOD,
    TOK_DIV,
    TOK_LITERALQ,
    TOK_LITERALQQ,
    TOK_NUMBER,
    TOK_QNAME,
    TOK_NCNAME,
    TOK_DOLLAR
];




var XPathLocationPath = { label: "LocationPath" };
var XPathRelativeLocationPath = { label: "RelativeLocationPath" };
var XPathAbsoluteLocationPath = { label: "AbsoluteLocationPath" };
var XPathStep = { label: "Step" };
var XPathNodeTest = { label: "NodeTest" };
var XPathPredicate = { label: "Predicate" };
var XPathLiteral = { label: "Literal" };
var XPathExpr = { label: "Expr" };
var XPathPrimaryExpr = { label: "PrimaryExpr" };
var XPathVariableReference = { label: "Variablereference" };
var XPathNumber = { label: "Number" };
var XPathFunctionCall = { label: "FunctionCall" };
var XPathArgumentRemainder = { label: "ArgumentRemainder" };
var XPathPathExpr = { label: "PathExpr" };
var XPathUnionExpr = { label: "UnionExpr" };
var XPathFilterExpr = { label: "FilterExpr" };
var XPathDigits = { label: "Digits" };

var xpathNonTerminals = [
    XPathLocationPath,
    XPathRelativeLocationPath,
    XPathAbsoluteLocationPath,
    XPathStep,
    XPathNodeTest,
    XPathPredicate,
    XPathLiteral,
    XPathExpr,
    XPathPrimaryExpr,
    XPathVariableReference,
    XPathNumber,
    XPathFunctionCall,
    XPathArgumentRemainder,
    XPathPathExpr,
    XPathUnionExpr,
    XPathFilterExpr,
    XPathDigits
];


var Q_01 = { label: "?" };
var Q_MM = { label: "*" };
var Q_1M = { label: "+" };


var ASSOC_LEFT = true;






















var xpathGrammarRules =
  [
   [ XPathLocationPath, [ XPathRelativeLocationPath ], 18,
     passExpr ],
   [ XPathLocationPath, [ XPathAbsoluteLocationPath ], 18,
     passExpr ],

   [ XPathAbsoluteLocationPath, [ TOK_SLASH, XPathRelativeLocationPath ], 18,
     makeLocationExpr1 ],
   [ XPathAbsoluteLocationPath, [ TOK_DSLASH, XPathRelativeLocationPath ], 18,
     makeLocationExpr2 ],

   [ XPathAbsoluteLocationPath, [ TOK_SLASH ], 0,
     makeLocationExpr3 ],
   [ XPathAbsoluteLocationPath, [ TOK_DSLASH ], 0,
     makeLocationExpr4 ],

   [ XPathRelativeLocationPath, [ XPathStep ], 31,
     makeLocationExpr5 ],
   [ XPathRelativeLocationPath,
     [ XPathRelativeLocationPath, TOK_SLASH, XPathStep ], 31,
     makeLocationExpr6 ],
   [ XPathRelativeLocationPath,
     [ XPathRelativeLocationPath, TOK_DSLASH, XPathStep ], 31,
     makeLocationExpr7 ],

   [ XPathStep, [ TOK_DOT ], 33,
     makeStepExpr1 ],
   [ XPathStep, [ TOK_DDOT ], 33,
     makeStepExpr2 ],
   [ XPathStep,
     [ TOK_AXISNAME, TOK_AXIS, XPathNodeTest ], 33,
     makeStepExpr3 ],
   [ XPathStep, [ TOK_AT, XPathNodeTest ], 33,
     makeStepExpr4 ],
   [ XPathStep, [ XPathNodeTest ], 33,
     makeStepExpr5 ],
   [ XPathStep, [ XPathStep, XPathPredicate ], 33,
     makeStepExpr6 ],

   [ XPathNodeTest, [ TOK_ASTERISK ], 33,
     makeNodeTestExpr1 ],
   [ XPathNodeTest, [ TOK_NCNAME, TOK_COLON, TOK_ASTERISK ], 33,
     makeNodeTestExpr2 ],
   [ XPathNodeTest, [ TOK_QNAME ], 33,
     makeNodeTestExpr3 ],
   [ XPathNodeTest, [ TOK_NODEO, TOK_PARENC ], 33,
     makeNodeTestExpr4 ],
   [ XPathNodeTest, [ TOK_NODEO, XPathLiteral, TOK_PARENC ], 33,
     makeNodeTestExpr5 ],

   [ XPathPredicate, [ TOK_BRACKO, XPathExpr, TOK_BRACKC ], 33,
     makePredicateExpr ],

   [ XPathPrimaryExpr, [ XPathVariableReference ], 33,
     passExpr ],
   [ XPathPrimaryExpr, [ TOK_PARENO, XPathExpr, TOK_PARENC ], 33,
     makePrimaryExpr ],
   [ XPathPrimaryExpr, [ XPathLiteral ], 30,
     passExpr ],
   [ XPathPrimaryExpr, [ XPathNumber ], 30,
     passExpr ],
   [ XPathPrimaryExpr, [ XPathFunctionCall ], 30,
     passExpr ],

   [ XPathFunctionCall, [ TOK_QNAME, TOK_PARENO, TOK_PARENC ], -1,
     makeFunctionCallExpr1 ],
   [ XPathFunctionCall,
     [ TOK_QNAME, TOK_PARENO, XPathExpr, XPathArgumentRemainder, Q_MM,
       TOK_PARENC ], -1,
     makeFunctionCallExpr2 ],
   [ XPathArgumentRemainder, [ TOK_COMMA, XPathExpr ], -1,
     makeArgumentExpr ],

   [ XPathUnionExpr, [ XPathPathExpr ], 20,
     passExpr ],
   [ XPathUnionExpr, [ XPathUnionExpr, TOK_PIPE, XPathPathExpr ], 20,
     makeUnionExpr ],

   [ XPathPathExpr, [ XPathLocationPath ], 20,
     passExpr ],
   [ XPathPathExpr, [ XPathFilterExpr ], 19,
     passExpr ],
   [ XPathPathExpr,
     [ XPathFilterExpr, TOK_SLASH, XPathRelativeLocationPath ], 20,
     makePathExpr1 ],
   [ XPathPathExpr,
     [ XPathFilterExpr, TOK_DSLASH, XPathRelativeLocationPath ], 20,
     makePathExpr2 ],

   [ XPathFilterExpr, [ XPathPrimaryExpr, XPathPredicate, Q_MM ], 20,
     makeFilterExpr ],

   [ XPathExpr, [ XPathPrimaryExpr ], 16,
     passExpr ],
   [ XPathExpr, [ XPathUnionExpr ], 16,
     passExpr ],

   [ XPathExpr, [ TOK_MINUS, XPathExpr ], -1,
     makeUnaryMinusExpr ],

   [ XPathExpr, [ XPathExpr, TOK_OR, XPathExpr ], -1,
     makeBinaryExpr ],
   [ XPathExpr, [ XPathExpr, TOK_AND, XPathExpr ], -1,
     makeBinaryExpr ],

   [ XPathExpr, [ XPathExpr, TOK_EQ, XPathExpr ], -1,
     makeBinaryExpr ],
   [ XPathExpr, [ XPathExpr, TOK_NEQ, XPathExpr ], -1,
     makeBinaryExpr ],

   [ XPathExpr, [ XPathExpr, TOK_LT, XPathExpr ], -1,
     makeBinaryExpr ],
   [ XPathExpr, [ XPathExpr, TOK_LE, XPathExpr ], -1,
     makeBinaryExpr ],
   [ XPathExpr, [ XPathExpr, TOK_GT, XPathExpr ], -1,
     makeBinaryExpr ],
   [ XPathExpr, [ XPathExpr, TOK_GE, XPathExpr ], -1,
     makeBinaryExpr ],

   [ XPathExpr, [ XPathExpr, TOK_PLUS, XPathExpr ], -1,
     makeBinaryExpr, ASSOC_LEFT ],
   [ XPathExpr, [ XPathExpr, TOK_MINUS, XPathExpr ], -1,
     makeBinaryExpr, ASSOC_LEFT ],

   [ XPathExpr, [ XPathExpr, TOK_ASTERISK, XPathExpr ], -1,
     makeBinaryExpr, ASSOC_LEFT ],
   [ XPathExpr, [ XPathExpr, TOK_DIV, XPathExpr ], -1,
     makeBinaryExpr, ASSOC_LEFT ],
   [ XPathExpr, [ XPathExpr, TOK_MOD, XPathExpr ], -1,
     makeBinaryExpr, ASSOC_LEFT ],

   [ XPathLiteral, [ TOK_LITERALQ ], -1,
     makeLiteralExpr ],
   [ XPathLiteral, [ TOK_LITERALQQ ], -1,
     makeLiteralExpr ],

   [ XPathNumber, [ TOK_NUMBER ], -1,
     makeNumberExpr ],

   [ XPathVariableReference, [ TOK_DOLLAR, TOK_QNAME ], 200,
     makeVariableReference ]
   ];





var xpathRules = [];

function xpathParseInit() {
  if (xpathRules.length) {
    return;
  }

  
  
  

  xpathGrammarRules.sort(function(a,b) {
    var la = a[1].length;
    var lb = b[1].length;
    if (la < lb) {
      return 1;
    } else if (la > lb) {
      return -1;
    } else {
      return 0;
    }
  });

  var k = 1;
  for (var i = 0; i < xpathNonTerminals.length; ++i) {
    xpathNonTerminals[i].key = k++;
  }

  for (i = 0; i < xpathTokenRules.length; ++i) {
    xpathTokenRules[i].key = k++;
  }

  xpathLog('XPath parse INIT: ' + k + ' rules');

  
  
  
  
  
  
  
  

  function push_(array, position, element) {
    if (!array[position]) {
      array[position] = [];
    }
    array[position].push(element);
  }

  for (i = 0; i < xpathGrammarRules.length; ++i) {
    var rule = xpathGrammarRules[i];
    var pattern = rule[1];

    for (var j = pattern.length - 1; j >= 0; --j) {
      if (pattern[j] == Q_1M) {
        push_(xpathRules, pattern[j-1].key, rule);
        break;

      } else if (pattern[j] == Q_MM || pattern[j] == Q_01) {
        push_(xpathRules, pattern[j-1].key, rule);
        --j;

      } else {
        push_(xpathRules, pattern[j].key, rule);
        break;
      }
    }
  }

  xpathLog('XPath parse INIT: ' + xpathRules.length + ' rule bins');

  var sum = 0;
  mapExec(xpathRules, function(i) {
    if (i) {
      sum += i.length;
    }
  });

  xpathLog('XPath parse INIT: ' + (sum / xpathRules.length) +
           ' average bin size');
}



function xpathCollectDescendants(nodelist, node) {
  for (var n = node.firstChild; n; n = n.nextSibling) {
    nodelist.push(n);
    arguments.callee(nodelist, n);
  }
}

function xpathCollectDescendantsReverse(nodelist, node) {
  for (var n = node.lastChild; n; n = n.previousSibling) {
    nodelist.push(n);
    arguments.callee(nodelist, n);
  }
}




function xpathDomEval(expr, node) {
  var expr1 = xpathParse(expr);
  var ret = expr1.evaluate(new ExprContext(node));
  return ret;
}



function xpathSort(input, sort) {
  if (sort.length == 0) {
    return;
  }

  var sortlist = [];

  for (var i = 0; i < input.contextSize(); ++i) {
    var node = input.nodelist[i];
    var sortitem = { node: node, key: [] };
    var context = input.clone(node, 0, [ node ]);

    for (var j = 0; j < sort.length; ++j) {
      var s = sort[j];
      var value = s.expr.evaluate(context);

      var evalue;
      if (s.type == 'text') {
        evalue = value.stringValue();
      } else if (s.type == 'number') {
        evalue = value.numberValue();
      }
      sortitem.key.push({ value: evalue, order: s.order });
    }

    
    
    
    sortitem.key.push({ value: i, order: 'ascending' });

    sortlist.push(sortitem);
  }

  sortlist.sort(xpathSortByKey);

  var nodes = [];
  for (var i = 0; i < sortlist.length; ++i) {
    nodes.push(sortlist[i].node);
  }
  input.nodelist = nodes;
  input.setNode(0);
}









function xpathSortByKey(v1, v2) {
  
  

  for (var i = 0; i < v1.key.length; ++i) {
    var o = v1.key[i].order == 'descending' ? -1 : 1;
    if (v1.key[i].value > v2.key[i].value) {
      return +1 * o;
    } else if (v1.key[i].value < v2.key[i].value) {
      return -1 * o;
    }
  }

  return 0;
}




function xpathEval(select, context) {
  var expr = xpathParse(select);
  var ret = expr.evaluate(context);
  return ret;
}

// end of ../ajaxslt/xpath.js

// begin ../ajaxslt/xslt.js










































function xsltProcess(xmlDoc, stylesheet) {
  var output = domCreateDocumentFragment(new XDocument);
  xsltProcessContext(new ExprContext(xmlDoc), stylesheet, output);
  var ret = xmlText(output);
  return ret;
}







function xsltProcessContext(input, template, output) {
  var outputDocument = xmlOwnerDocument(output);

  var nodename = template.nodeName.split(/:/);
  if (nodename.length == 1 || nodename[0] != 'xsl') {
    xsltPassThrough(input, template, output, outputDocument);

  } else {
    switch(nodename[1]) {
    case 'apply-imports':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'apply-templates':
      var select = xmlGetAttribute(template, 'select');
      var nodes;
      if (select) {
        nodes = xpathEval(select,input).nodeSetValue();
      } else {
        nodes = input.node.childNodes;
      }

      var sortContext = input.clone(nodes[0], 0, nodes);
      xsltWithParam(sortContext, template);
      xsltSort(sortContext, template);

      var mode = xmlGetAttribute(template, 'mode');
      var top = template.ownerDocument.documentElement;
      var templates = [];
      for (var i = 0; i < top.childNodes.length; ++i) {
        var c = top.childNodes[i];
        if (c.nodeType == DOM_ELEMENT_NODE &&
            c.nodeName == 'xsl:template' &&
            c.getAttribute('mode') == mode) {
          templates.push(c);
        }
      }
      for (var j = 0; j < sortContext.contextSize(); ++j) {
        var nj = sortContext.nodelist[j];
        for (var i = 0; i < templates.length; ++i) {
          xsltProcessContext(sortContext.clone(nj, j), templates[i], output);
        }
      }
      break;

    case 'attribute':
      var nameexpr = xmlGetAttribute(template, 'name');
      var name = xsltAttributeValue(nameexpr, input);
      var node = domCreateDocumentFragment(outputDocument);
      xsltChildNodes(input, template, node);
      var value = xmlValue(node);
      domSetAttribute(output, name, value);
      break;

    case 'attribute-set':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'call-template':
      var name = xmlGetAttribute(template, 'name');
      var top = template.ownerDocument.documentElement;

      var paramContext = input.clone();
      xsltWithParam(paramContext, template);

      for (var i = 0; i < top.childNodes.length; ++i) {
        var c = top.childNodes[i];
        if (c.nodeType == DOM_ELEMENT_NODE &&
            c.nodeName == 'xsl:template' &&
            domGetAttribute(c, 'name') == name) {
          xsltChildNodes(paramContext, c, output);
          break;
        }
      }
      break;

    case 'choose':
      xsltChoose(input, template, output);
      break;

    case 'comment':
      var node = domCreateDocumentFragment(outputDocument);
      xsltChildNodes(input, template, node);
      var commentData = xmlValue(node);
      var commentNode = domCreateComment(outputDocument, commentData);
      output.appendChild(commentNode);
      break;

    case 'copy':
      var node = xsltCopy(output, input.node, outputDocument);
      if (node) {
        xsltChildNodes(input, template, node);
      }
      break;

    case 'copy-of':
      var select = xmlGetAttribute(template, 'select');
      var value = xpathEval(select, input);
      if (value.type == 'node-set') {
        var nodes = value.nodeSetValue();
        for (var i = 0; i < nodes.length; ++i) {
          xsltCopyOf(output, nodes[i], outputDocument);
        }

      } else {
        var node = domCreateTextNode(outputDocument, value.stringValue());
        domAppendChild(output, node);
      }
      break;

    case 'decimal-format':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'element':
      var nameexpr = xmlGetAttribute(template, 'name');
      var name = xsltAttributeValue(nameexpr, input);
      var node = domCreateElement(outputDocument, name);
      domAppendChild(output, node);
      xsltChildNodes(input, template, node);
      break;

    case 'fallback':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'for-each':
      xsltForEach(input, template, output);
      break;

    case 'if':
      var test = xmlGetAttribute(template, 'test');
      if (xpathEval(test, input).booleanValue()) {
        xsltChildNodes(input, template, output);
      }
      break;

    case 'import':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'include':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'key':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'message':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'namespace-alias':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'number':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'otherwise':
      alert('error if here: ' + nodename[1]);
      break;

    case 'output':
      
      
      
      
      break;

    case 'preserve-space':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'processing-instruction':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'sort':
      
      break;

    case 'strip-space':
      alert('not implemented: ' + nodename[1]);
      break;

    case 'stylesheet':
    case 'transform':
      xsltChildNodes(input, template, output);
      break;

    case 'template':
      var match = xmlGetAttribute(template, 'match');
      if (match && xsltMatch(match, input)) {
        xsltChildNodes(input, template, output);
      }
      break;

    case 'text':
      var text = xmlValue(template);
      var node = domCreateTextNode(outputDocument, text);
      output.appendChild(node);
      break;

    case 'value-of':
      var select = xmlGetAttribute(template, 'select');
      var value = xpathEval(select, input).stringValue();
      var node = domCreateTextNode(outputDocument, value);
      output.appendChild(node);
      break;

    case 'param':
      xsltVariable(input, template, false);
      break;

    case 'variable':
      xsltVariable(input, template, true);
      break;

    case 'when':
      alert('error if here: ' + nodename[1]);
      break;

    case 'with-param':
      alert('error if here: ' + nodename[1]);
      break;

    default:
      alert('error if here: ' + nodename[1]);
      break;
    }
  }
}







function xsltWithParam(input, template) {
  for (var i = 0; i < template.childNodes.length; ++i) {
    var c = template.childNodes[i];
    if (c.nodeType == DOM_ELEMENT_NODE && c.nodeName == 'xsl:with-param') {
      xsltVariable(input, c, true);
    }
  }
}









function xsltSort(input, template) {
  var sort = [];
  for (var i = 0; i < template.childNodes.length; ++i) {
    var c = template.childNodes[i];
    if (c.nodeType == DOM_ELEMENT_NODE && c.nodeName == 'xsl:sort') {
      var select = xmlGetAttribute(c, 'select');
      var expr = xpathParse(select);
      var type = xmlGetAttribute(c, 'data-type') || 'text';
      var order = xmlGetAttribute(c, 'order') || 'ascending';
      sort.push({ expr: expr, type: type, order: order });
    }
  }

  xpathSort(input, sort);
}










function xsltVariable(input, template, override) {
  var name = xmlGetAttribute(template, 'name');
  var select = xmlGetAttribute(template, 'select');

  var value;

  if (template.childNodes.length > 0) {
    var root = domCreateDocumentFragment(template.ownerDocument);
    xsltChildNodes(input, template, root);
    value = new NodeSetValue([root]);

  } else if (select) {
    value = xpathEval(select, input);

  } else {
    value = new StringValue('');
  }

  if (override || !input.getVariable(name)) {
    input.setVariable(name, value);
  }
}





function xsltChoose(input, template, output) {
  for (var i = 0; i < template.childNodes.length; ++i) {
    var childNode = template.childNodes[i];
    if (childNode.nodeType != DOM_ELEMENT_NODE) {
      continue;

    } else if (childNode.nodeName == 'xsl:when') {
      var test = xmlGetAttribute(childNode, 'test');
      if (xpathEval(test, input).booleanValue()) {
        xsltChildNodes(input, childNode, output);
        break;
      }

    } else if (childNode.nodeName == 'xsl:otherwise') {
      xsltChildNodes(input, childNode, output);
      break;
    }
  }
}




function xsltForEach(input, template, output) {
  var select = xmlGetAttribute(template, 'select');
  var nodes = xpathEval(select, input).nodeSetValue();
  var sortContext = input.clone(nodes[0], 0, nodes);
  xsltSort(sortContext, template);
  for (var i = 0; i < sortContext.contextSize(); ++i) {
    var ni = sortContext.nodelist[i];
    xsltChildNodes(sortContext.clone(ni, i), template, output);
  }
}






function xsltChildNodes(input, template, output) {
  
  
  var context = input.clone();
  for (var i = 0; i < template.childNodes.length; ++i) {
    xsltProcessContext(context, template.childNodes[i], output);
  }
}







function xsltPassThrough(input, template, output, outputDocument) {
  if (template.nodeType == DOM_TEXT_NODE) {
    if (xsltPassText(template)) {
      var node = domCreateTextNode(outputDocument, template.nodeValue);
      domAppendChild(output, node);
    }

  } else if (template.nodeType == DOM_ELEMENT_NODE) {
    var node = domCreateElement(outputDocument, template.nodeName);
    for (var i = 0; i < template.attributes.length; ++i) {
      var a = template.attributes[i];
      if (a) {
        var name = a.nodeName;
        var value = xsltAttributeValue(a.nodeValue, input);
        domSetAttribute(node, name, value);
      }
    }
    domAppendChild(output, node);
    xsltChildNodes(input, template, node);

  } else {
    
    
    xsltChildNodes(input, template, output);
  }
}









function xsltPassText(template) {
  if (!template.nodeValue.match(/^\s*$/)) {
    return true;
  }

  var element = template.parentNode;
  if (element.nodeName == 'xsl:text') {
    return true;
  }

  while (element && element.nodeType == DOM_ELEMENT_NODE) {
    var xmlspace = domGetAttribute(element, 'xml:space');
    if (xmlspace) {
      if (xmlspace == 'default') {
        return false;
      } else if (xmlspace == 'preserve') {
        return true;
      }
    }

    element = element.parentNode;
  }

  return false;
}








function xsltAttributeValue(value, context) {
  var parts = stringSplit(value, '{');
  if (parts.length == 1) {
    return value;
  }

  var ret = '';
  for (var i = 0; i < parts.length; ++i) {
    var rp = stringSplit(parts[i], '}');
    if (rp.length != 2) {
      
      ret += parts[i];
      continue;
    }

    var val = xpathEval(rp[0], context).stringValue();
    ret += val + rp[1];
  }

  return ret;
}







function xmlGetAttribute(node, name) {
  
  
  
  var value = domGetAttribute(node, name);
  if (value) {
    return xmlResolveEntities(value);
  } else {
    return value;
  }
};










function xsltCopyOf(dst, src, dstDocument) {
  if (src.nodeType == DOM_DOCUMENT_FRAGMENT_NODE ||
      src.nodeType == DOM_DOCUMENT_NODE) {
    for (var i = 0; i < src.childNodes.length; ++i) {
      arguments.callee(dst, src.childNodes[i], dstDocument);
    }
  } else {
    var node = xsltCopy(dst, src, dstDocument);
    if (node) {
      
      
      for (var i = 0; i < src.attributes.length; ++i) {
        arguments.callee(node, src.attributes[i], dstDocument);
      }

      for (var i = 0; i < src.childNodes.length; ++i) {
        arguments.callee(node, src.childNodes[i], dstDocument);
      }
    }
  }
}










function xsltCopy(dst, src, dstDocument) {
  if (src.nodeType == DOM_ELEMENT_NODE) {
    var node = domCreateElement(dstDocument, src.nodeName);
    domAppendChild(dst, node);
    return node;
  }

  if (src.nodeType == DOM_TEXT_NODE) {
    var node = domCreateTextNode(dstDocument, src.nodeValue);
    domAppendChild(dst, node);

  } else if (src.nodeType == DOM_CDATA_SECTION_NODE) {
    var node = domCreateCDATASection(dstDocument, src.nodeValue);
    domAppendChild(dst, node);

  } else if (src.nodeType == DOM_COMMENT_NODE) {
    var node = domCreateComment(dstDocument, src.nodeValue);
    domAppendChild(dst, node);

  } else if (src.nodeType == DOM_ATTRIBUTE_NODE) {
    domSetAttribute(dst, src.nodeName, src.nodeValue);
  }

  return null;
}




function xsltMatch(match, context) {
  var expr = xpathParse(match);

  var ret;
  
  if (expr.steps && !expr.absolute && expr.steps.length == 1 &&
      expr.steps[0].axis == 'child' && expr.steps[0].predicate.length == 0) {
    ret = expr.steps[0].nodetest.evaluate(context).booleanValue();

  } else {

    ret = false;
    var node = context.node;

    while (!ret && node) {
      var result = expr.evaluate(context.clone(node,0,[node])).nodeSetValue();
      for (var i = 0; i < result.length; ++i) {
        if (result[i] == context.node) {
          ret = true;
          break;
        }
      }
      node = node.parentNode;
    }
  }

  return ret;
}

// end of ../ajaxslt/xslt.js

// begin json2.js
if(!this.JSON){JSON=function(){function f(n){return n<10?'0'+n:n}Date.prototype.toJSON=function(key){return this.getUTCFullYear()+'-'+f(this.getUTCMonth()+1)+'-'+f(this.getUTCDate())+'T'+f(this.getUTCHours())+':'+f(this.getUTCMinutes())+':'+f(this.getUTCSeconds())+'Z'};var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapeable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\': '\\\\'},rep;function quote(string){escapeable.lastIndex=0;return escapeable.test(string)?'"'+string.replace(escapeable,function(a){var c=meta[a];if(typeof c==='string'){return c}return'\\u'+('0000'+(+(a.charCodeAt(0))).toString(16)).slice(-4)})+'"':'"'+string+'"'}function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key)}if(typeof rep==='function'){value=rep.call(holder,key,value)}switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null'}gap+=indent;partial=[];if(typeof value.length==='number'&&!(value.propertyIsEnumerable('length'))){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null'}v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v}if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value,rep);if(v){partial.push(quote(k)+(gap?': ':':')+v)}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value,rep);if(v){partial.push(quote(k)+(gap?': ':':')+v)}}}}v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v}}return{stringify:function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' '}}else if(typeof space==='string'){indent=space}rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify')}return str('',{'':value})},parse:function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v}else{delete value[k]}}}}return reviver.call(holder,key,value)}cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+('0000'+(+(a.charCodeAt(0))).toString(16)).slice(-4)})}if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j}throw new SyntaxError('JSON.parse')}}}()}
// end of json2.js

// begin json_dict.js
 

 
if(json_dict instanceof Array == false){
var json_dict = new Array();
}

 
function GenJsonDictSelect(dicttype, selid)
{
	var elem_select = document.getElementById(selid);
	if (elem_select == null || elem_select == undefined)
	{
		bNull = true;
		elem_select = document.createElement("select");
	}
	for (dataid in json_dict[dicttype])
	{
		if (isNaN(parseInt(dataid)))
		{
			continue;
		}
		elem_option = document.createElement("option");
		elem_option.setAttribute("value", json_dict[dicttype][dataid]["dataid"]);
		elem_option.appendChild(document.createTextNode(json_dict[dicttype][dataid]["dataname"]));
		elem_select.appendChild(elem_option);
	}
}

 
function JsonDictSelInit(dicttype, selid, selvalue)
{
	if (typeof(json_dict[dicttype]) == "undefined")
    {
		return;
    }
	GenJsonDictSelect(dicttype, selid);
	if (selvalue.length == 0)
	{
		return;
	}
	selelement = document.getElementById(selid);
	selelement.value = selvalue;
	setTimeout("selelement.value="+selvalue,0);
}


// end of json_dict.js

// begin json_cate.js
var json_cate=new Array();function JsonCateSetValue(catetype,cellid,selects){var elem=document.getElementById(cellid);elem.value=json_cate[catetype][0]["cateid"];for(var i=0;i<selects.length;i++){if(selects[i].value.indexOf("_0")>0){break}elem.value+="_"+selects[i].value}}function GenJsonCateSelect(catetype,cellid,cateid,cateshow,maxlevel){var elem_cell=document.getElementById(cellid);var elem_div=elem_cell.parentNode;var selects=elem_div.getElementsByTagName("select");var k,l,cnt,i,j;var bNull=false;if(cateid.indexOf("_0")>0){k=parseInt(cateid.substr(0,cateid.indexOf("_0")))+1;l=selects.length-1;while(l>=k){elem_div.removeChild(selects[k]);l--}JsonCateSetValue(catetype,cellid,selects);return}cnt=json_cate[catetype][cateid]["catepath"].split("_").length-1;if(json_cate[catetype][cateid]["subcate"].length==0){k=cnt;l=selects.length-1;while(l>=k){elem_div.removeChild(selects[k]);l--}JsonCateSetValue(catetype,cellid,selects);return}var elem_select=selects[cnt];if(elem_select==null||elem_select==undefined){bNull=true;elem_select=document.createElement("select");elem_select.onchange=function(){GenJsonCateSelect(catetype,cellid,this.value,null,maxlevel)}}else{elem_select.options.length=0;k=cnt+1;l=selects.length-1;while(l>=k){elem_div.removeChild(selects[k]);l--}}var elem_option=document.createElement("option");elem_option.setAttribute("value",cnt+"_0");var menu=json_cate[catetype.concat("_menu")][cnt];if(menu==undefined){elem_option.appendChild(document.createTextNode("[请选择]"))}else{elem_option.appendChild(document.createTextNode("[".concat(menu,"]")))}elem_select.appendChild(elem_option);var showids='';if(cateshow){if(cateshow!='undefined'&&cateshow!="all"){var showids=cateshow.split(",")}}for(i in json_cate[catetype][cateid]["subcate"]){if(isNaN(parseInt(i))){continue}j=json_cate[catetype][cateid]["subcate"][i];if(showids!=''){for(var sh=0;sh<showids.length;sh++){if(showids[sh]>0&&showids[sh]==json_cate[catetype][j]["cateid"]){elem_option=document.createElement("option");elem_option.setAttribute("value",json_cate[catetype][j]["cateid"]);elem_option.appendChild(document.createTextNode(json_cate[catetype][j]["catename"]));elem_select.appendChild(elem_option)}}}else{elem_option=document.createElement("option");elem_option.setAttribute("value",json_cate[catetype][j]["cateid"]);elem_option.appendChild(document.createTextNode(json_cate[catetype][j]["catename"]));elem_select.appendChild(elem_option)}}if(cnt==0){elem_div.appendChild(elem_select)}else{if(bNull&&(maxlevel==undefined||maxlevel==0||cnt<maxlevel)){elem_div.insertBefore(elem_select,null)}}JsonCateSetValue(catetype,cellid,selects)}function JsonCateInit(catetype,cellid,cellpath,menu,cateshow,maxlevel){if(menu.length==0){json_cate[catetype.concat("_menu")]=new Array()}else{json_cate[catetype.concat("_menu")]=menu.split(",")}if(typeof(json_cate[catetype])=="undefined"){return}if(typeof(maxlevel)=="undefined"||maxlevel==""){maxlevel=0}GenJsonCateSelect(catetype,cellid,json_cate[catetype][0]["cateid"],cateshow,maxlevel);if(cellpath.length==0){return}var cateids=cellpath.split("_");var selects;for(var i=1;i<cateids.length;i++){selects=document.getElementById(cellid).parentNode.getElementsByTagName("select");selects[selects.length-1].value=cateids[i];GenJsonCateSelect(catetype,cellid,cateids[i],null,maxlevel)}}function onInterestTopSelect(obj){var elems=obj.parentNode.parentNode.getElementsByTagName("input");for(var i=1;i<elems.length;i++){elems[i].checked=obj.checked}}
// end of json_cate.js

// begin json_parse.js
 

 

 

json_parse = function () {







    var at,     
        ch,     
        escapee = {
            '"':  '"',
            '\\': '\\',
            '/':  '/',
            b:    '\b',
            f:    '\f',
            n:    '\n',
            r:    '\r',
            t:    '\t'
        },
        text,

        error = function (m) {



            throw {
                name:    'SyntaxError',
                message: m,
                at:      at,
                text:    text
            };
        },

        next = function (c) {



            if (c && c !== ch) {
                error("Expected '" + c + "' instead of '" + ch + "'");
            }




            ch = text.charAt(at);
            at += 1;
            return ch;
        },

        number = function () {



            var number,
                string = '';

            if (ch === '-') {
                string = '-';
                next('-');
            }
            while (ch >= '0' && ch <= '9') {
                string += ch;
                next();
            }
            if (ch === '.') {
                string += '.';
                while (next() && ch >= '0' && ch <= '9') {
                    string += ch;
                }
            }
            if (ch === 'e' || ch === 'E') {
                string += ch;
                next();
                if (ch === '-' || ch === '+') {
                    string += ch;
                    next();
                }
                while (ch >= '0' && ch <= '9') {
                    string += ch;
                    next();
                }
            }
            number = +string;
            if (isNaN(number)) {
                error("Bad number");
            } else {
                return number;
            }
        },

        string = function () {



            var hex,
                i,
                string = '',
                uffff;



            if (ch === '"') {
                while (next()) {
                    if (ch === '"') {
                        next();
                        return string;
                    } else if (ch === '\\') {
                        next();
                        if (ch === 'u') {
                            uffff = 0;
                            for (i = 0; i < 4; i += 1) {
                                hex = parseInt(next(), 16);
                                if (!isFinite(hex)) {
                                    break;
                                }
                                uffff = uffff * 16 + hex;
                            }
                            string += String.fromCharCode(uffff);
                        } else if (typeof escapee[ch] === 'string') {
                            string += escapee[ch];
                        } else {
                            break;
                        }
                    } else {
                        string += ch;
                    }
                }
            }
            error("Bad string");
        },

        white = function () {

// Skip whitespace.

            while (ch && ch <= ' ') {
                next();
            }
        },

        word = function () {

// true, false, or null.

            switch (ch) {
            case 't':
                next('t');
                next('r');
                next('u');
                next('e');
                return true;
            case 'f':
                next('f');
                next('a');
                next('l');
                next('s');
                next('e');
                return false;
            case 'n':
                next('n');
                next('u');
                next('l');
                next('l');
                return null;
            }
            error("Unexpected '" + ch + "'");
        },

        value,  // Place holder for the value function.

        array = function () {

// Parse an array value.

            var array = [];

            if (ch === '[') {
                next('[');
                white();
                if (ch === ']') {
                    next(']');
                    return array;   // empty array
                }
                while (ch) {
                    array.push(value());
                    white();
                    if (ch === ']') {
                        next(']');
                        return array;
                    }
                    next(',');
                    white();
                }
            }
            error("Bad array");
        },

        object = function () {

// Parse an object value.

            var key,
                object = {};

            if (ch === '{') {
                next('{');
                white();
                if (ch === '}') {
                    next('}');
                    return object;   // empty object
                }
                while (ch) {
                    key = string();
                    white();
                    next(':');
                    object[key] = value();
                    white();
                    if (ch === '}') {
                        next('}');
                        return object;
                    }
                    next(',');
                    white();
                }
            }
            error("Bad object");
        };

    value = function () {

// Parse a JSON value. It could be an object, an array, a string, a number,
// or a word.

        white();
        switch (ch) {
        case '{':
            return object();
        case '[':
            return array();
        case '"':
            return string();
        case '-':
            return number();
        default:
            return ch >= '0' && ch <= '9' ? number() : word();
        }
    };

// Return the json_parse function. It will have access to all of the above
// functions and variables.

    return function (source, reviver) {
        var result;

        text = source;
        at = 0;
        ch = ' ';
        result = value();
        white();
        if (ch) {
            error("Syntax error");
        }

// If there is a reviver function, we recursively walk the new structure,
// passing each name/value pair to the reviver function for possible
// transformation, starting with a temporary boot object that holds the result
// in an empty key. If there is not a reviver function, we simply return the
// result.

        return typeof reviver === 'function' ?
            function walk(holder, key) {
                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }({'': result}, '') : result;

    };
}();



// end of json_parse.js

// begin MessageBox.js
var g_RegMessageBox = new Object();

if (typeof(showDebug) == 'undefined') {
	showDebug = function(msg) {
		alert(msg);
	}
}

function getInt(_default, str) {
	if (typeof(str) == "string" || typeof(str) == "number") {
		var n = parseInt(str);
		if (!isNaN(n)) {
			return str;
		}
	}
	return _default;
}

Style = function(_left, _top, _width, _height, _position, _zIndex) {
	this.left = getInt(null, _left);
	this.top = getInt(null, _top);
	this.width = getInt(null, _width);
	this.height = getInt(null, _height);
	this.position = getInt(null, _position);
	this.zIndex = getInt(null, _zIndex);
}

 
function randomId(prefix) {
	while (true) {
		var id = prefix + Math.round(Math.random() * 10000);
		var obj = document.getElementById(id);
		if ((obj == null)
				&& (g_RegMessageBox[id] == undefined)
					|| (g_RegMessageBox[id] == null)) {
			return id;
		}
	}
}

 
function run(toRun) {
	try {
		if (toRun != undefined && toRun != null) {
			if (typeof(toRun) == 'string') {
				return eval(toRun);
			} else if (typeof(toRun) == 'function') {
				return toRun();
			}
		}
	} catch (e) {
		showDebug("exception:" + e);
	}
}

function createTextDiv(text) {
	var div = document.createElement("div");
	if (typeof(text) == "string") {
		var text = document.createTextNode(text);
		div.appendChild(text);
	}
	return div;
}

function createHtmlDiv(text) {
	var div = document.createElement("div");
	if (typeof(text) == "string") {
		div.innerHTML = text;
	}
	return div;
}

function resizeWindow(button, operate) {
	var id = button.parentNode.parentNode.parentNode.parentNode.id;
	var box = g_RegMessageBox[id];
	if (box == undefined || box == null) {
		showDebug('not find messagebox:' + id);
		return;
	}
	var obj = document.getElementById(id);
	if (obj == null) {
		return;
	}
	var frame = button.parentNode.parentNode.parentNode;
	if (typeof(operate) == "string") {
		if (operate == "max") {
			frame.style.left = 0;
			frame.style.top = 0;
			frame.style.width = "100%";
			frame.style.height = "100%";
			return;
		} else if (operate == "min") {
			frame.style.left = 0;
			frame.style.top = 0;
			frame.style.height = "20pt";
			return;
		}
	}
	showDebug("unknown resize operate.");
}

MessageFrame = function(title, url, boxType, act_ok, act_cancel) {
	var divTitle = createTextDiv(title);
	var htmlContent = '<iframe class="mb_iframe" frameborder="0" src="' + encodeURI(url) + '"></iframe>';
	return new BoxBase(divTitle.innerHTML, htmlContent, boxType, act_ok, act_cancel);
}

StatusBar = function(content) {
	var divContent = createHtmlDiv(content);
	return new BoxBase("", divContent.innerHTML, "status", null, null, null, "status_outer");
}

MessageBox = function(title, content, boxType, act_ok, act_cancel) {
	var divTitle = createTextDiv(title);
	var divContent = createHtmlDiv(content);
	return new BoxBase(divTitle.innerHTML, divContent.innerHTML, boxType, act_ok, act_cancel);
}

PopWindow = function(title, innerHTML, boxType, act_ok, act_cancel, _style) {
	var divTitle = createTextDiv(title);
	var divContent = createHtmlDiv(innerHTML);
	return new BoxBase(divTitle.innerHTML, divContent.innerHTML, boxType, act_ok, act_cancel, _style);
}

 
BoxBase = function(htmlTitle, htmlContent, boxType, act_ok, act_cancel, _style, _class) {
	var id = randomId('messagebox_');
	this.m_id = id;
	this.title = htmlTitle;
	this.content = htmlContent;
	this.act_ok = act_ok;
	this.act_cancel = act_cancel;
	this.eleBox = null;
	g_RegMessageBox[id] = this;

	var body = document.getElementsByTagName("body")[0];
	if (body == undefined) {
		showDebug("failed to get body");
		return;
	}
	var mainInstance = this;

	if (typeof(boxType) == 'string') {
		boxType = boxType.toLowerCase();
	}

	var clsOuter;
	if (typeof(_class) == "string" && _class.length > 0) {
		clsOuter = _class;
	} else {
		clsOuter = "mb_outer";
	}
	 
	var divOuter = document.createElement('div');
	divOuter.id = this.m_id;
	divOuter.className = clsOuter;
	body.appendChild(divOuter);
	
	this.eleBox = divOuter;

	 
	var divPrev = document.createElement('iframe');
	divPrev.className = "mb_prev"; 
	divPrev.setAttribute("scrolling","no");
	divPrev.setAttribute("frameborder",0);
	divPrev.setAttribute("marginwidth",0);
	divOuter.appendChild(divPrev);

	 
	

	 
	var divFrame = document.createElement('div');
	divFrame.className = "mb_frame";
	divFrame.id = randomId("mb_frame_id_");
	divOuter.appendChild(divFrame);
	 
	
	var bdy = (document.documentElement && document.documentElement.clientWidth)?document.documentElement:document.body;
	var _yHeight = bdy.clientHeight - document.getElementById(divFrame.id).offsetHeight;
	

	
    var bHeight = Math.max(
        Math.max(document.body.scrollHeight, document.documentElement.scrollHeight),
        Math.max(document.body.offsetHeight, document.documentElement.offsetHeight)
    );
    divPrev.style.height = bHeight + "px";
    var bWidth;
    if (document.all)
        bWidth = document.body.offsetWidth;
    else
     bWidth = Math.max(
        Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
        Math.max(document.body.offsetWidth, document.documentElement.offsetWidth)
    );
    divPrev.style.width = bWidth + "px";
	
	if ((typeof(_style) == "object") && (_style != null)) {
		if (_style.left != null) {
			divFrame.style.left = _style.left;
		}
		if (_style.top != null) {
			divFrame.style.top = _style.top;
		}
		if (_style.width != null) {
			divFrame.style.width = _style.width;
		}
		if (_style.height != null) {
			divFrame.style.height = _style.height;
		}
		if (_style.position != null) {
			divFrame.style.position = _style.position;
		}
		if (_style.zIndex != null) {
			divFrame.style.zIndex = _style.zIndex;
		}
	}

	 
	var divNext = document.createElement('div');
	divNext.className = "mb_next";
	divOuter.appendChild(divNext);

	if (boxType != "status") {
	 
	var divTitle = document.createElement('div');
	divTitle.className = "mb_title";
	divFrame.appendChild(divTitle);

	 
	divTitleLeft = document.createElement('div');
	divTitleLeft.className = "mb_title_left";
	divTitleLeft.innerHTML = htmlTitle;
	divTitle.appendChild(divTitleLeft);

	 
	divTitleRight = document.createElement('div');
	divTitleRight.className = "mb_title_right";
	divTitle.appendChild(divTitleRight);

	 
	 
	var buttonClose = document.createElement('input');
	buttonClose.setAttribute("type", "image");
	buttonClose.setAttribute("alt", "close");
	buttonClose.setAttribute("title", "关闭");
	buttonClose.setAttribute("src", "/common/image/MessageBox/close.gif");
	buttonClose.className = "mb_close";
	buttonClose.onclick = function() { mainInstance.oncancel(); };
	divTitleRight.appendChild(buttonClose);
	
	
	}

	 
	var divContent = document.createElement('div');
	divContent.className = "mb_content";
	divContent.innerHTML = htmlContent;
	divFrame.appendChild(divContent);

	if (boxType != "status" && boxType != "popwindow") {
	var divConfirm = document.createElement("div");
	divConfirm.className = "mb_confirm";
	divFrame.appendChild(divConfirm);
	}

	 
	if (boxType == 'confirm' || boxType == 'info' || boxType == 'error' || boxType == 'warning') {
		var buttonOk = document.createElement('input');
		buttonOk.setAttribute("type", "button");
		buttonOk.setAttribute("value", "确定");
		
		
		
		buttonOk.onclick = function() { mainInstance.onok(); };
		divConfirm.appendChild(buttonOk);
	}

	 
	if (boxType == 'confirm') {
		var buttonCancel = document.createElement('input');
		buttonCancel.setAttribute("type", "button");
		buttonCancel.setAttribute("value", "取消");
		
		
		
		buttonCancel.onclick = function() { mainInstance.oncancel(); };
		divConfirm.appendChild(buttonCancel);
	}
	
	 
	 
	divFrame.style.position = "absolute";
	var aleft = (bdy.clientWidth - divFrame.offsetWidth)/2 + bdy.scrollLeft;
	var atop = (bdy.clientHeight - divFrame.offsetHeight)/2 + bdy.scrollTop;

	divFrame.style.left = aleft + "px";
	divFrame.style.top = atop + "px";
	if(buttonOk!=null) buttonOk.focus();
	drag(divFrame.id,"mb_title_left");

	return this;
}

BoxBase.prototype.close = function() {
	var id = this.m_id;
	if (typeof(id) != 'string') {
		showDebug('invalid messagebox id:' + id);
		return;
	}
	var box = g_RegMessageBox[id];
	if (box == undefined || box == null) {
		showDebug('not find messagebox:' + id);
		return;
	}

	var obj = document.getElementById(id);
	if (obj != null) {
		var isIE = /msie/.test(navigator.userAgent.toLowerCase());
		if(isIE)
		{
			for(var i=0;i<obj.childNodes.length;i++)
			{
				var child = obj.childNodes[i];
				if(child.className == 'mb_frame')
				{
					var iframes = child.getElementsByTagName('iframe');
					if(iframes.length>0)
					{
						var iframe=iframes[0];
						iframe.contentWindow.document.execCommand("SelectAll",false,"");
					}
				}
			}
		}
		var p = obj.parentNode;
		if (p != null) {
			p.removeChild(obj);
		}
	}
	g_RegMessageBox[id] = null;
}

BoxBase.prototype.id = function() {
	return this.m_id;
}

BoxBase.prototype.onok = function() {
	var isframeact = false;
	if(this.act_ok == 'Frame_ActOK')
	{
		isframeact = true;
		var msgobj = document.getElementById(this.m_id);
		var framelist = msgobj.getElementsByTagName('iframe');
		if(framelist.length > 0)
		{	
			var framedoc = framelist[0].contentWindow.document;
			var msgidobj = framedoc.getElementById('id_popdialog');
			if(msgidobj != null)
			{
				 msgidobj.value = this.m_id;
			}
			if(typeof(framelist[0].contentWindow.ActOk) == 'function')
			{
				this.act_ok = framelist[0].contentWindow.ActOk;
			}
		}
	}
	if(this.act_ok == 'Frame_ActOK')
	{
		this.close();
		return result;
	}
	else
	{
		var result = run(this.act_ok);
		if(isframeact && !result)
		{
			this.act_ok = 'Frame_ActOK';
			return result;
		}
		this.close();
		return result;
	}
}

BoxBase.prototype.oncancel = function() {
	var isframeact = false;
	if(this.act_cancel == 'Frame_ActCancel')
	{
		isframeact = true;
		var msgobj = document.getElementById(this.m_id);
		var framelist = msgobj.getElementsByTagName('iframe');
		if(framelist.length > 0)
		{	
			var framedoc = framelist[0].contentWindow.document;
			var msgidobj = framedoc.getElementById('id_popdialog');
			if(msgidobj != null)
			{
				 msgidobj.value = this.m_id;
			}
			if(typeof(framelist[0].contentWindow.ActCancel) == 'function')
			{
				this.act_cancel = framelist[0].contentWindow.ActCancel;
			}
		}
	}
	var result = run(this.act_cancel);
	this.close();
	return result;
}
function drag(el,bar){
    var box=document.getElementById(el); 
    var t=bar;
    var ie=!!(window.attachEvent && !window.opera);
    var par=false; 
    var elx, ely; 
    var x,y; 

	 
	 
	
    box.onmousedown = FocusIt; 
    function FocusIt(e){
        var target = ie ? event.srcElement : e.target;
        if(target.className!=t) return true; 
		document.onmousemove = MoveIt; 
		document.onmouseup = ClearIt; 
		box.style.position = "absolute";
		box.style.left = box.offsetLeft+"px";
		box.style.top = box.offsetTop+"px";
		x= ie ? event.clientX : e.clientX;
		y= ie ? event.clientY : e.clientY;
		elx = parseInt(box.style.left);
		ely = parseInt(box.style.top);
	    par = true;
    }
    function MoveIt(e){
		if (!par) return false;
		var getX;
		var getY;
	

        var bHeight = Math.max(
            Math.max(document.body.scrollHeight, document.documentElement.scrollHeight),
            Math.max(document.body.offsetHeight, document.documentElement.offsetHeight)
        );

        var bWidth;
        if (document.all)
            bWidth = document.body.offsetWidth;
        else
         bWidth = Math.max(
            Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
            Math.max(document.body.offsetWidth, document.documentElement.offsetWidth)
        );
        var maxX = bWidth -  box.offsetWidth;
        var maxY = bHeight - box.offsetHeight;

		if(ie)
		{
			getX = (event.clientX + (elx-x))<0?0:(event.clientX + (elx-x));
			getY = (event.clientY + (ely-y))<0?0:(event.clientY + (ely-y));
		}
		else
		{
			getX = (e.clientX + (elx-x))<0?0:(e.clientX + (elx-x));
			getY = (e.clientY + (ely-y))<0?0:(e.clientY + (ely-y));
		}
		getX = getX>maxX?maxX:getX;
		getY = getY>maxY?maxY:getY;
        box.style.left = getX + "px";
        box.style.top = getY + "px";
		
    }
    function ClearIt(){   
        par = false;
		
		document.onmousemove = ""; 
		document.onmouseup = "";
        
        
    }

    function SetCookie(sName, sValue){
        document.cookie = sName + "=" + escape(sValue) + "; ";
    }
    function GetCookie(sName){
        var aCookie = document.cookie.split("; ");
        for (var i=0; i < aCookie.length; i++){
            var aCrumb = aCookie[i].split("=");
            if (sName == aCrumb[0])
            return unescape(aCrumb[1]);
        }
    }
}

// end of MessageBox.js

// begin function.js
 

 
charset="UTF-8";
var Url = {

    
    encode : function (string) {
        return escape(this._utf8_encode(string));
    },

    
    decode : function (string) {
        return this._utf8_decode(unescape(string));
    },

    
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

};

 
function GetCookieValue(strName)
{
	var mycookie = document.cookie.split("; ");
	for (var i = 0; i < mycookie.length; i++)
	{
		var cookie1 = mycookie[i].split("=");
		if (cookie1[0] == strName)
		{
			return Url.decode(cookie1[1]);
		}
	}
	return null;
}


var CWDF_USERNAME = GetCookieValue("username");
function cwdf_username()
{
	return CWDF_USERNAME;
}

function cwdf_islogin()
{
	var CWDF_PASSWORD = GetCookieValue("password");
	if (CWDF_USERNAME != "" && CWDF_USERNAME != null && CWDF_PASSWORD != "" && CWDF_PASSWORD != null)
	{
		return true;
	}
	return false;
}

function IsDate(sDate)
{
	if (sDate == null || sDate.length < 1)
	{
		return false;
	}
	var iaMonthDays = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
	var iaDate = new Array(3);
	var year, month, day;

	iaDate = sDate.split("-");

	if (iaDate.length != 3)
	{
		return false;
	}

	if (iaDate[1].length > 2 || iaDate[2].length > 2)
	{
		return false;
	}

	year = parseInt(iaDate[0]);
	month = parseInt(iaDate[1]);
	day	= parseInt(iaDate[2]);

	if (isNaN(year) || isNaN(month) || isNaN(day))
	{
		return false;
	}

	if (year < 1900 || year > 2100)
	{
		return false;
	}

	if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0))
	{
		iaMonthDays[1] = 29;
	}

	if (month < 1 || month > 12)
	{
		return false;
	}

	if (day < 1 || day > iaMonthDays[month - 1])
	{
		return false;
	}
	return true;
}

function IsTime(TimeString)
{
	if (TimeString == null || TimeString.length < 1)
	{
		return false;
	}

	var tempArray = TimeString.split(":");

	var temph;
	var tempm;
	var temps;

	if (isNaN(tempArray[1]))
	{
		tempArray[1] = 0;
	}

	if (isNaN(tempArray[2]))
	{
		tempArray[2] = 0;
	}

	temph = parseInt(tempArray[0]);
	tempm = parseInt(tempArray[1]);
	temps = parseInt(tempArray[2]);

	if (isNaN(temph) || isNaN(tempm) || isNaN(temps))
	{
		return false;
	}

	if (temph < 0 || temph > 23)
	{
		return false;
	}
	if (tempm < 0 || tempm > 59)
	{
		return false;
	}
	if (temps < 0 || temps > 59)
	{
		return false;
	}
	return true;
}

function IsDateTime(strDateTime)
{
	if (strDateTime == null || strDateTime.length < 5)
	{
		return false;
	}

	var tempArray = strDateTime.split(" ");
	var tempDate = tempArray[0];
	var tempTime;
	if (isNaN(tempArray[1]))
	{
		tempTime = "00:00:00";
	}

	if (IsDate(tempDate) && IsTime(tempTime))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function IsInteger(strInt)
{
	var iTemp = parseInt(strInt, 10);
	if (isNaN(iTemp) || iTemp != strInt)
	{
		return false;
	}
	return true;
}

function IsFloat(strFloat)
{
	var fTemp = parseFloat(strFloat);
	if (isNaN(fTemp) || fTemp != strFloat)
	{
		return false;
	}
	return true;
}

function IsEmail(strEmail)
{
	var filter=/^\s*([A-Za-z0-9_-]+(\.\w+)*@(\w+\.)+\w{2,4})\s*$/;
	return filter.test(strEmail);
}

function Days(iYear, iMonth)
{
	var Days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	if (IsLeapYear(iYear))
	{
		Days[1] = 29;
	}
	return Days[iMonth-1];
}

function IsLeapYear(iYear)
{
	return (iYear % 4 == 0 && iYear % 100 != 0) || iYear % 400 == 0;
}

 
function openwindow(strUrl, iwidth, iheight)
{
        window.open(strUrl, null, 'height=' + iheight + ',width=' + iwidth + ',status=no,toolbar=no,menubar=no,location=no');
}

 
function parentRedirect(strUrl)
{
        window.opener.location.href = strUrl;
}

 
function addFavorite(strUrl, iWidth, iHeight)
{
	openwindow(strUrl + "&title=" + encodeURI(document.title) + "&url=" + encodeURI(location.href), iWidth, iHeight);
}

 
function addBookmark(anchor)
{
    if (window.external)
    {
          window.external.AddFavorite(anchor.getAttribute("href"), document.title);
          return false;
    }
    return true;
}
 
function KeyEnterEval(event, evaljs)
{
	if (event.keyCode == 13){
		event.returnValue=false;
		event.cancel = true;
		eval(evaljs);
	}
}

function escapeXml(str){
	if(typeof(str)=='string'&&str.length>0){
		return str.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;").replace(/'/g, "&apos;");
	}
	return "";
}
function unescapeXml(str){
	if(typeof(str)=='string'&&str.length>0){
		return str.replace(/&amp;/g, "&").replace(/&gt;/g, ">").replace(/&lt;/g, "<").replace(/&quot;/g, "\"").replace(/&apos;/g, "'");
	}
	return "";
}
function URLDecode(encoded)
{
   var HEXCHARS = "0123456789ABCDEFabcdef";
   var plaintext = "";
   var i = 0;
   while (i < encoded.length) {
       var ch = encoded.charAt(i);
	   if (ch == "+") {
	       plaintext += " ";
		   i++;
	   } else if (ch == "%") {
			if (i < (encoded.length-2)
					&& HEXCHARS.indexOf(encoded.charAt(i+1)) != -1
					&& HEXCHARS.indexOf(encoded.charAt(i+2)) != -1 ) {
				plaintext += unescape( encoded.substr(i,3) );
				i += 3;
			} else {
				alert( 'Bad escape combination near ...' + encoded.substr(i) );
				plaintext += "%[ERROR]";
				i++;
			}
		} else {
		   plaintext += ch;
		   i++;
		}
	} 
   return plaintext;
};

function showHidElem(){
	for(var i=0;i<arguments.length;i++){
		var obj=document.getElementById(arguments[i]);
		if(obj!=null&&typeof(obj)=="object"){
			obj.style.display="";
		}
	}
}

function showHidElemByName(){
	for(var i=0;i<arguments.length;i++){
		var objArray=document.getElementsByName(arguments[i]);
		if(objArray!=null&&objArray.length>0){
			for(var j=0;j<objArray.length;j++){
				objArray[j].style.display="";
			}
		}
	}
}

function hiddenElem(){
	for(var i=0;i<arguments.length;i++){
		var obj=document.getElementById(arguments[i]);
		if(obj!=null&&typeof(obj)=="object"){
			obj.style.display="none";
		}
	}
}

function hiddenElemByName(){
	for(var i=0;i<arguments.length;i++){
		var objArray=document.getElementByName(arguments[i]);
		if(objArray!=null&&objArray.length>0){
			for(var j=0;j<objArray.length;j++){
				objArray[j].style.display="none";
			}
		}
	}
}

 
function GetRadioValue(widgetId,name)
{
	if (widgetId == '' || name == '') return null;
	widget = document.getElementById(widgetId);
	childObj = widget.getElementsByTagName('input');
	for (var i=0; i<childObj.length; i++) {
		if (childObj[i].getAttribute('name') == name) {
			if (childObj[i].checked)
				return childObj[i].value;
		}
	}
	return null;
}

 
function GetCheckboxValue(widgetId, name)
{
	if (widgetId == '' || name == '') return null;
	widget = document.getElementById(widgetId);
	childObj = widget.getElementsByTagName('input');
	var value = new Array();
	for (var i=0; i<childObj.length; i++) {
		if (childObj[i].getAttribute('name') == name) {
			if (childObj[i].checked)
				value.push(childObj[i].value);
		}
	}
	return value;

}

 
function ChangeCheckBoxStatus(name, flag)
{
	if (name == '')
		return false;
	if (flag != true && flag != false)
		return false;
	var obj = document.getElementsByName(name);
	if (obj == null)
		return false;
	for (var i=0; i<obj.length; i++) {
		obj[i].checked = flag;
	}
	return true;
}

function AddBusiLog()
{
	var logRequest = new XDocument();
	var root = logRequest.createElement("busilog");
	logRequest.appendChild(root);

	var eleReferer = logRequest.createElement("referer");
	eleReferer.appendChild(logRequest.createTextNode(document.URL));
	root.appendChild(eleReferer);

	var eleReferFrom = logRequest.createElement("referfrom");
	eleReferFrom.appendChild(logRequest.createTextNode(document.referrer));
	root.appendChild(eleReferFrom);

	var userid = GetCookieValue("userid");
	var eleUserId = logRequest.createElement("userid");
	eleUserId.appendChild(logRequest.createTextNode(userid));
	root.appendChild(eleUserId);

	var usertype = GetCookieValue("usertype");
	var eleUserType = logRequest.createElement("usertype");
	eleUserType.appendChild(logRequest.createTextNode(usertype));
	root.appendChild(eleUserType);

	var xmlhttp = createXMLHttpRequest();
	if (xmlhttp)
	{
		xmlhttp.open("POST", "common/pvlog.php", true);
		xmlhttp.setRequestHeader("Content-Type", "text/xml");
		var data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" + xmlText(logRequest);
		xmlhttp.send(data);
	}
}

 
function SetShow(widgetid,objname,show)
{
	if(!show) return;
	var objs = document.getElementsByName(objname);
	if(objs.length)
	{
		for(var i=0;i<objs.length;i++)
		{
			objs[i].style.display = 'inline';
		}
	}
}

 
function AddBookmark(url, title)
{
	if (window.sidebar) {
		
		window.sidebar.addPanel(title, url,"");
	} else if( window.external ) {
		
		window.external.AddFavorite( url, title);
	} 
	else {
		
		alert("暂不支持你当前使用的浏览器");	
	}
}


// end of function.js

