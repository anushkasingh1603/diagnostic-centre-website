-- Seed services (no id; assumes auto-increment)

INSERT INTO services (name, slug, short_description, full_description, is_active, sort_order) VALUES
('1.5T MRI with 3T Features', 'mri', 'Detailed, painless imaging of soft tissues at any angle.', 'MRI is a safe, painless scan that produces clear images of the body\'s tissues from any angle, and is especially useful for detecting soft-tissue conditions throughout the body.', 1, 1),
('32 Slice CT Scan', 'ct-scan', 'Fast cross-sectional imaging for accurate diagnosis.', 'A CT scan combines a series of X-rays with computer processing to build detailed cross-sectional and 3D images of soft tissues and bones, helping detect a wide range of conditions and injuries.', 1, 2),
('4D USG & Colour Doppler', 'ultrasound', 'Sound-wave imaging for organs and pregnancy.', 'Ultrasound uses sound waves to create images of internal organs. It helps assess pain, swelling and infection, and is widely used to monitor pregnancy.', 1, 3),
('Echocardiography', 'echo', 'Non-invasive ultrasound of the heart.', 'An echocardiogram is a non-invasive ultrasound test used to examine the heart\'s structure and function and to evaluate various heart conditions.', 1, 4),
('Digital X-Ray with Image Intensifier', 'x-ray', 'Clear digital imaging of bones and soft tissue.', 'Digital X-ray imaging examines most areas of the body, primarily bones and joints, and can also help assess certain soft-tissue and internal conditions.', 1, 5),
('EEG', 'eeg', 'Measures brain activity to investigate seizures.', 'An EEG records the brain\'s electrical activity and is mainly used to detect and investigate epilepsy and other conditions involving seizures.', 1, 6),
('EMG', 'emg', 'Assesses muscle and nerve health.', 'Electromyography evaluates the health of muscles and the nerve cells that control them, helping identify nerve or muscle dysfunction and signalling problems.', 1, 7),
('NCV', 'ncv', 'Tests nerve signal speed alongside EMG.', 'Nerve conduction velocity testing is often performed with an EMG to distinguish between nerve disorders and muscle disorders.', 1, 8),
('Endoscopy', 'endoscopy', 'Non-surgical exam of the digestive tract.', 'Endoscopy is a non-surgical procedure used to examine the digestive tract using a flexible scope with a light and camera.', 1, 9),
('Colonoscopy', 'colonoscopy', 'Examines the bowel for polyps and other issues.', 'A colonoscopy examines the bowel using a flexible scope with a light and camera, helping identify issues such as polyps, inflammation and colorectal conditions.', 1, 10),
('FNAC', 'fnac', 'Simple tool for diagnosing lymph node conditions.', 'Fine-needle aspiration cytology is a simple, useful tool for diagnosing lymph node conditions and detecting metastatic or primary disease.', 1, 11),
('Pathology', 'pathology', 'Laboratory testing for accurate diagnosis.', 'Pathology provides diagnostic information through laboratory testing, supporting the diagnosis and management of a wide range of conditions.', 1, 12);

