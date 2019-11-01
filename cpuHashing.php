<!DOCTYPE html>
<html lang="en">
	<head>
		<title>CPU Hasher</title>
		<meta charset="utf-8">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/core.js" integrity="sha256-wPN6ojtZcdUXfbQ+nxh6Zm7xh1pOWxEbuE9EIa1P7BY=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.min.js" integrity="sha256-pMgPw2sArXEcjAzvhVgWZ4iS4Jm3NKrLG0mFWdrUwCE=" crossorigin="anonymous"></script>
	</head>
	<body>
		<input type="text" id="input">
		<button onclick="Process()">Process</button>
		<br>
		<br>
		<p>Count numbers: <output id="result"></output></p>
		<button onclick="startWorker()">Start Worker</button>
		<button onclick="stopWorker()">Stop Worker</button>
		<p id="demo"></p>
	</body>
	<script>
var w;

function startWorker() {
  if (typeof(Worker) !== "undefined") {
    if (typeof(w) == "undefined") {
      w = new Worker("webworkers/combinationsMaker.js");
    }
    w.onmessage = function(event) {
      document.getElementById("result").innerHTML = event.data;
    };
  } else {
    document.getElementById("result").innerHTML = "Sorry! No Web Worker support.";
  }
}

function stopWorker() {
  w.terminate();
  w = undefined;
}
</script>
	<script>
		var combos;
		
		var strings = [];
		var hashes = [];
		
		function StartProcess(){
			for(var i = 0; i < combos.length; i++){
				var md5_hash = CryptoJS.MD5(combos[i]);
				strings.push(combos[i]);
				hashes.push(md5_hash.toString());
			}
			SendOff(strings,hashes);
		}
		
		function Process(){
			var input = document.getElementById("input");
			
			combos = combinations(input.value);
			StartProcess();
		}
		
		function SendOff(strings,hashes){
			//console.log(json);
			$.ajax({
				method: "POST",
				url: "cpuHasher.php",
				data: { string: strings, hash:hashes}
			})
			.done(function(msg){
				console.log(msg);
			});
		}
		
		function GenerateRandomNumber(min,max){
			return Math.floor(Math.random() * max) + min;
		}
		
		function GenerateRandomString(length) {
   			var result           = '';
   			var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   			var charactersLength = characters.length;
   			for ( var i = 0; i < length; i++ ) {
      			result += characters.charAt(Math.floor(Math.random() * charactersLength));
   			}
   			return result;
		}
		
		function combinations(str) {
			var fn = function(active, rest, a) {
				if (!active && !rest)
					return;
				if (!rest) {
					a.push(active);
				} else {
					fn(active + rest[0], rest.slice(1), a);
					fn(active, rest.slice(1), a);
				}
				return a;
			}
			return fn("", str, []);
		}
		
		function isArray(a){var g=a.constructor.toString();
   if(g.match(/function Array()/)){return true;}else{return false;}
}
function objtostring(o){var a,k,f,freg=[],txt; if(typeof o!='object'){return false;}
   if(isArray(o)){a={'t1':'[','t2':']','isarray':true}
   }else         {a={'t1':'{','t2':'}','isarray':false}}; txt=a.t1;
   for(k in o){
           if(!a.isarray)txt+="'"+k+"':";
           if(typeof o[k]=='string'){txt+="'"+o[k]+"',";
           }else if(typeof o[k]=='number'||typeof o[k]=='boolean'){txt+=o[k]+",";
           }else if(typeof o[k]=='function'){f=o[k].toString();freg=f.match(/^function\s+(\w+)\s*\(/);
               if(freg){txt+=freg[1]+",";}else{txt+=f+",";};
           }else if(typeof o[k]=='object'){txt+=objtostring(o[k])+",";
           }
   }return txt.substr(0,txt.length-1)+a.t2;
}
	</script>
</html>