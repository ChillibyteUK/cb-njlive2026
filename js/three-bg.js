(function(){
  function start(){
    // Create or reuse canvas
    var canvas = document.getElementById('three-bg-canvas');
    if (!canvas) {
      canvas = document.createElement('canvas');
      canvas.id = 'three-bg-canvas';
      canvas.className = 'three-bg-canvas';
      document.body.prepend(canvas);
    }

    var THREE = window.THREE;
    var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.setSize(window.innerWidth, window.innerHeight);

    var scene = new THREE.Scene();
    var camera = new THREE.OrthographicCamera(-1,1,1,-1,0,1);

    var uniforms = {
      uTime:{value:0},
      uResolution:{value:new THREE.Vector2(window.innerWidth, window.innerHeight)},
      uMouse:{value:new THREE.Vector2(0.5,0.5)},
      uEnergy:{value:0},
      uColA:{value:new THREE.Vector3()},
      uColB:{value:new THREE.Vector3()},
      uColC:{value:new THREE.Vector3()},
      uRatioA:{value:1},
      uRatioB:{value:1},
      uRatioW:{value:0.7}
    };

    var PALETTE_RATIOS = {
      'mint-blue': { a: 1.45, b: 0.80, w: 0.60 },
      'violet':    { a: 1.25, b: 0.40, w: 0.75 },
      'warm':      { a: 1.20, b: 1.00, w: 0.55 }
    };

    function readPalette() {
      var raw = getComputedStyle(document.documentElement).getPropertyValue('--bg-active').trim();
      return raw.split(',').map(function(v){ return v.trim().split(/\s+/).map(Number); });
    }
    function hslToRgb(h,s,l){
      s = Math.min(1,(s/100)*1.25);
      l /= 100;
      function k(n){ return (n+h/30)%12; }
      var a = s * Math.min(l,1-l);
      function f(n){ return l - a * Math.max(-1, Math.min(k(n)-3, Math.min(9-k(n),1))); }
      return [f(0), f(8), f(4)];
    }

    function applyPalette(){
      var mode = document.documentElement.dataset.bg || 'mint-blue';
      var cols = readPalette().map(function(c){ var rgb = hslToRgb(c[0],c[1],c[2]); return rgb; });
      var r = PALETTE_RATIOS[mode] || PALETTE_RATIOS['mint-blue'];

      uniforms.uColA.value.set(cols[0][0], cols[0][1], cols[0][2]);
      uniforms.uColB.value.set(cols[1][0], cols[1][1], cols[1][2]);
      uniforms.uColC.value.set(cols[2][0], cols[2][1], cols[2][2]);
      uniforms.uRatioA.value = r.a;
      uniforms.uRatioB.value = r.b;
      uniforms.uRatioW.value = r.w;
    }
    applyPalette();

    var material = new THREE.ShaderMaterial({
      uniforms: uniforms,
      vertexShader: 'void main(){gl_Position=vec4(position,1.0);}',
      fragmentShader: [
        'precision highp float;',
        'uniform vec2 uResolution;','uniform vec2 uMouse;','uniform float uTime;','uniform float uEnergy;',
        'uniform vec3 uColA;','uniform vec3 uColB;','uniform vec3 uColC;','uniform float uRatioA;','uniform float uRatioB;','uniform float uRatioW;',
        'float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}',
        'float noise(vec2 p){ vec2 i=floor(p), f=fract(p); vec2 u=f*f*(3.-2.*f); return mix(mix(hash(i),hash(i+vec2(1.,0.)),u.x), mix(hash(i+vec2(0.,1.)),hash(i+vec2(1.,1.)),u.x),u.y); }',
        'float dither(vec2 uv){ return fract(sin(dot(uv * uResolution.xy, vec2(12.9898,78.233)))*43758.5453); }',
        'vec2 curl(vec2 p){ float e=.002; return vec2(noise(p+vec2(0.,e))-noise(p-vec2(0.,e)), noise(p+vec2(e,0.))-noise(p-vec2(e,0.))); }',
        'void main(){',
        ' vec2 uv=gl_FragCoord.xy/uResolution; float ar=uResolution.x/uResolution.y; uv.x*=ar;',
        ' float t=uTime*0.45;',
        ' vec2 flow=curl(uv*0.5 + t)*1.6 + curl(uv*1.2 - t)*1.2 + curl(uv*2.2 + t*0.6);',
        ' vec2 m=uMouse; m.x*=ar; vec2 d=uv-m; float r=length(d); float brush=exp(-r*r*5.0);',
        ' vec2 smudge=normalize(flow+0.0001); vec2 softCurl=vec2(-d.y,d.x)*0.3; flow += (smudge*2.0+softCurl)*brush*(1.2+uEnergy);',
        ' uv += flow*0.04;',
        ' float n1=noise(uv*1.0+t); float n2=noise(uv*2.0-t*0.8);',
        ' float m1=pow(smoothstep(0.2,0.8,n1),0.65+uEnergy*0.35);',
        ' float m2=pow(smoothstep(0.25,0.85,n2),0.65+uEnergy*0.35);',
        ' vec3 col = uColC*uRatioW + uColA*m1*uRatioA + uColB*m2*uRatioB; col /= (uRatioW + m1*uRatioA*0.6 + m2*uRatioB*0.6);',
        ' float whiteMix=mix(0.74,0.60,uEnergy); col=mix(uColC,col,whiteMix);',
        ' float luma=dot(col,vec3(0.2126,0.7152,0.0722)); col=mix(vec3(luma),col,mix(1.15,1.55,uEnergy)); col=col*col*(3.0-2.0*col); col=pow(col,vec3(0.66));',
        ' float n=dither(gl_FragCoord.xy/uResolution); col += (n - 0.5) * 0.018;',
        ' float film=fract(sin(dot(gl_FragCoord.xy + uTime*3.0, vec2(91.7,27.4))) * 43758.5453); col += (film - 0.5) * 0.008;',
        ' gl_FragColor=vec4(col,1.0);',
        '}'
      ].join('\n')
    });

    var mesh = new THREE.Mesh(new THREE.PlaneGeometry(2,2), material);
    scene.add(mesh);

    var lastScroll = window.scrollY || 0;
    var energy = 0;

    window.addEventListener('mousemove', function(e){
      uniforms.uMouse.value.set(e.clientX / window.innerWidth, 1 - e.clientY / window.innerHeight);
      energy += 0.04;
    }, { passive: true });

    window.addEventListener('scroll', function(){
      var sy = window.scrollY || 0;
      energy += Math.min(Math.abs(sy - lastScroll) / 400, 0.2);
      lastScroll = sy;
    }, { passive: true });

    function animate(t){
      uniforms.uTime.value = (t || performance.now()) * 0.001;
      energy *= 0.92;
      uniforms.uEnergy.value = Math.min(energy, 1);
      renderer.render(scene, camera);
      requestAnimationFrame(animate);
    }
    requestAnimationFrame(animate);

    window.addEventListener('resize', function(){
      renderer.setSize(window.innerWidth, window.innerHeight);
      uniforms.uResolution.value.set(window.innerWidth, window.innerHeight);
    });

    // Re-apply palette when data attribute changes (basic observer)
    var obs = new MutationObserver(function(muts){
      muts.forEach(function(m){ if (m.attributeName === 'data-bg') applyPalette(); });
    });
    obs.observe(document.documentElement, { attributes: true });
  }

  if (window.THREE) {
    start();
  } else {
    window.addEventListener('threejs:ready', start, { once: true });
  }
})();
