import * as THREE from 'three';
import { FBXLoader } from 'three/addons/loaders/FBXLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

// 1. SETUP DASAR
const container = document.getElementById('illustration-3d-container') || document.body;
const scene = new THREE.Scene();
scene.background = new THREE.Color(0x000000); // Background hitam sesuai gambar

const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.set(5, 5, 5);

const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(window.devicePixelRatio);
container.appendChild(renderer.domElement);

// 2. PENCAHAYAAN
const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 1.2);
directionalLight.position.set(5, 10, 7.5);
scene.add(directionalLight);

// 3. HELPER (Axes Custom)
// Membuat sumbu yang memanjang ke dua arah (positif & negatif)
const axisLength = 100;
const createAxis = (color, start, end) => {
    const geometry = new THREE.BufferGeometry().setFromPoints([
        new THREE.Vector3(...start),
        new THREE.Vector3(...end)
    ]);
    const material = new THREE.LineBasicMaterial({ color: color });
    return new THREE.Line(geometry, material);
};

scene.add(createAxis(0xff0000, [-axisLength, 0, 0], [axisLength, 0, 0])); // X - Merah
scene.add(createAxis(0x00ff00, [0, -axisLength, 0], [0, axisLength, 0])); // Y - Hijau
scene.add(createAxis(0x0000ff, [0, 0, -axisLength], [0, 0, axisLength])); // Z - Biru

// 4. KONTROL KAMERA
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.dampingFactor = 0.05;

// 5. MEMUAT MODEL FBX
const fbxLoader = new FBXLoader();

fbxLoader.load(
    'model/wood/wood.fbx',
    (object) => {
        // Jadikan objek asli dari load sebagai "template" atau master
        const baseWood = object;

        // --- NORMALISASI ---
        // Menghitung ukuran asli model agar scale(1,1,1) nantinya tepat 1 meter
        const boxMaster = new THREE.Box3().setFromObject(baseWood);
        const sizeMaster = boxMaster.getSize(new THREE.Vector3());

        // Atur agar baseWood memiliki ukuran 1x1x1 secara internal
        baseWood.scale.set(1 / sizeMaster.x, 1 / sizeMaster.y, 1 / sizeMaster.z);


        // --- OBJEK 1 (3m x 0.5m x 0.2m) ---
        const wood1 = baseWood.clone();
        wood1.scale.set(0.5, 0.2, 3);
        wood1.position.set(-3, 0.2, 0.5);
        wood1.rotation.y = THREE.MathUtils.degToRad(90);
        scene.add(wood1);

        const wood2 = baseWood.clone();
        wood2.scale.set(0.5, 0.2, 2);
        wood2.position.set(-2.76, 0.2, 1.5);
        wood2.rotation.y = THREE.MathUtils.degToRad(90);
        scene.add(wood2);

        const wood3 = baseWood.clone();
        wood3.scale.set(0.4, 0.2, 2);
        wood3.position.set(-3, 0.6, 1);
        wood3.rotation.y = THREE.MathUtils.degToRad(90);
        scene.add(wood3);

        console.log('Model FBX berhasil dimuat dan dinormalisasi ke meter!');

        // Sesuaikan posisi kamera
        const box = new THREE.Box3().setFromObject(wood1);
        const size = box.getSize(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z);

        camera.position.set(maxDim * 0.2, maxDim * 0.2, maxDim * 0.2);
        camera.lookAt(0, 0, 0);
        controls.update();
    },
    (xhr) => {
        console.log((xhr.loaded / xhr.total * 100) + '% dimuat');
    },
    (error) => {
        console.error('Terjadi kesalahan saat memuat model:', error);
    }
);

// 6. ANIMATION LOOP
function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}

animate();

// Handle Resize
window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
});