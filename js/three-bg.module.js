import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.182.0/build/three.module.min.js';

// Create container and canvas
let container = document.querySelector('.three-bg-container');
if (!container) {
  container = document.createElement('div');
  container.className = 'three-bg-container';
  document.body.prepend(container);
}

let canvas = document.getElementById('three-bg-canvas');
if (!canvas) {
  canvas = document.createElement('canvas');
  canvas.id = 'three-bg-canvas';
  canvas.className = 'three-bg-canvas';
  container.appendChild(canvas);
}

const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
renderer.setSize(window.innerWidth, window.innerHeight);

const scene = new THREE.Scene();
const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);

const uniforms = {
  uTime: { value: 0 },
  uResolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
  uMouse: { value: new THREE.Vector2(0.5, 0.5) },
  uEnergy: { value: 0 },
  uColA: { value: new THREE.Vector3() },
  uColB: { value: new THREE.Vector3() },
  uColC: { value: new THREE.Vector3() },
  uColD: { value: new THREE.Vector3() },
  uRatioA: { value: 1 },
  uRatioB: { value: 1 },
  uRatioC: { value: 1 },
  uRatioW: { value: 0.7 },
};

const PALETTE_RATIOS = {
  'mint-blue': { a: 2.00, b: 1.50, c: 0, w: 0.15 },
  violet: { a: 1.25, b: 0.40, c: 0, w: 0.75 },
  warm: { a: 2.00, b: 2.00, c: 1.80, w: 0.10 },
};

function readPalette() {
  const raw = getComputedStyle(document.documentElement).getPropertyValue('--bg-active').trim();
  return raw.split(',').map((v) => v.trim().split(/\s+/).map(Number));
}

function applyPalette() {
  const mode = document.documentElement.dataset.bg || 'mint-blue';
  const cols = readPalette();
  const r = PALETTE_RATIOS[mode] || PALETTE_RATIOS['mint-blue'];

  // console.log('applyPalette called - mode:', mode);
  // console.log('Parsed colors:', cols);

  uniforms.uColA.value.set(cols[0][0], cols[0][1], cols[0][2]);
  uniforms.uColB.value.set(cols[1][0], cols[1][1], cols[1][2]);
  if (cols[2]) uniforms.uColC.value.set(cols[2][0], cols[2][1], cols[2][2]);
  if (cols[3]) uniforms.uColD.value.set(cols[3][0], cols[3][1], cols[3][2]);
  uniforms.uRatioA.value = r.a;
  uniforms.uRatioB.value = r.b;
  uniforms.uRatioC.value = r.c || 0;
  uniforms.uRatioW.value = r.w;

  // console.log('Uniforms set - uColA:', uniforms.uColA.value, 'uColB:', uniforms.uColB.value, 'uColC:', uniforms.uColC.value, 'uColD:', uniforms.uColD.value);
}
applyPalette();

const material = new THREE.ShaderMaterial({
  uniforms,
  vertexShader: 'void main(){gl_Position=vec4(position,1.0);}',
  fragmentShader: [
    'precision highp float;',
    'uniform vec2 uResolution;','uniform vec2 uMouse;','uniform float uTime;','uniform float uEnergy;',
    'uniform vec3 uColA;','uniform vec3 uColB;','uniform vec3 uColC;','uniform vec3 uColD;','uniform float uRatioA;','uniform float uRatioB;','uniform float uRatioC;','uniform float uRatioW;',
    'float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}',
    'float noise(vec2 p){ vec2 i=floor(p), f=fract(p); vec2 u=f*f*(3.-2.*f); return mix(mix(hash(i),hash(i+vec2(1.,0.)),u.x), mix(hash(i+vec2(0.,1.)),hash(i+vec2(1.,1.)),u.x),u.y); }',
    'float dither(vec2 uv){ return fract(sin(dot(uv * uResolution.xy, vec2(12.9898,78.233)))*43758.5453); }',
    'vec2 curl(vec2 p){ float e=.002; return vec2(noise(p+vec2(0.,e))-noise(p-vec2(0.,e)), noise(p+vec2(e,0.))-noise(p-vec2(e,0.))); }',
    'void main(){',
    ' vec2 uv=gl_FragCoord.xy/uResolution; float ar=uResolution.x/uResolution.y; uv.x*=ar;',
    ' float t=uTime*0.45;',
    ' vec2 flow=curl(uv*0.5 + t)*0.4 + curl(uv*1.2 - t)*0.3 + curl(uv*2.2 + t*0.6)*0.2;',
    ' vec2 m=uMouse; m.x*=ar; vec2 d=uv-m; float r=length(d); float brush=exp(-r*r*5.0);',
    ' vec2 smudge=normalize(flow+0.0001); vec2 softCurl=vec2(-d.y,d.x)*0.3; flow += (smudge*2.0+softCurl)*brush*(1.2+uEnergy);',
    ' uv += flow*0.04;',
    ' float n1=noise(uv*1.0+t); float n2=noise(uv*2.0-t*0.8); float n3=noise(uv*1.5+t*0.5);',
    ' float m1=pow(smoothstep(0.2,0.8,n1),0.65+uEnergy*0.35);',
    ' float m2=pow(smoothstep(0.25,0.85,n2),0.65+uEnergy*0.35);',
    ' float m3=pow(smoothstep(0.22,0.82,n3),0.65+uEnergy*0.35);',
    ' vec3 col = mix(mix(mix(uColD, uColA, m1*uRatioA*0.8), uColB, m2*uRatioB*0.8), uColC, m3*uRatioC*0.8);',
    ' float whiteMix=mix(0.74,0.60,uEnergy); col=mix(uColD,col,whiteMix);',
    ' float luma=dot(col,vec3(0.2126,0.7152,0.0722)); col=mix(vec3(luma),col,mix(1.05,1.15,uEnergy)); col=col*col*(3.0-2.0*col); col=pow(col,vec3(0.66));',
    ' gl_FragColor=vec4(col,1.0);',
    '}'
  ].join('\n'),
});

const mesh = new THREE.Mesh(new THREE.PlaneGeometry(2, 2), material);
scene.add(mesh);

let lastScroll = window.scrollY || 0;
let energy = 0;

window.addEventListener('mousemove', (e) => {
  uniforms.uMouse.value.set(e.clientX / window.innerWidth, 1 - e.clientY / window.innerHeight);
  energy += 0.04;
}, { passive: true });

window.addEventListener('scroll', () => {
  const sy = window.scrollY || 0;
  energy += Math.min(Math.abs(sy - lastScroll) / 400, 0.2);
  lastScroll = sy;
}, { passive: true });

function animate(t) {
  uniforms.uTime.value = (t || performance.now()) * 0.001;
  energy *= 0.92;
  uniforms.uEnergy.value = Math.min(energy, 1);
  renderer.render(scene, camera);
  requestAnimationFrame(animate);
}
requestAnimationFrame(animate);

window.addEventListener('resize', () => {
  renderer.setSize(window.innerWidth, window.innerHeight);
  uniforms.uResolution.value.set(window.innerWidth, window.innerHeight);
});

// Re-apply palette when data attribute changes (basic observer)
const obs = new MutationObserver((muts) => {
  muts.forEach((m) => { if (m.attributeName === 'data-bg') applyPalette(); });
});
obs.observe(document.documentElement, { attributes: true });

// End of three-bg.module.js