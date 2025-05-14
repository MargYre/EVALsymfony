-- Sélectionner la base de données
USE jobstep;

-- Créer un conseiller
INSERT INTO user (email, roles, password, nom, prenom, role) VALUES 
('conseiller@jobstep.com', '["ROLE_CONSEILLER"]', '$2y$13$qc0CeLj3OOJqrH4vDRAKAOLsNQNT5nWVNSWvF3UE3UNwMwF/tW/d.', 'Martin', 'Sophie', 'Conseiller');

-- Créer un candidat  
INSERT INTO user (email, roles, password, nom, prenom, role) VALUES
('candidat@jobstep.com', '["ROLE_CANDIDAT"]', '$2y$13$qc0CeLj3OOJqrH4vDRAKAOLsNQNT5nWVNSWvF3UE3UNwMwF/tW/d.', 'Dupont', 'Jean', 'Candidat');

-- Créer des ressources
INSERT INTO ressource (intitule, presentation, support, nature, url) VALUES
('Guide de rédaction CV', 'Un guide complet pour créer un CV professionnel et impactant.', 'PDF', 'guide', 'https://www.exemple.com/guide-cv.pdf'),
('Questionnaire de personnalité professionnelle', 'Un questionnaire pour identifier vos forces et motivations professionnelles.', 'form', 'formulaire', 'https://www.exemple.com/questionnaire-personnalite'),
('Techniques d\'entretien', 'Vidéo présentant les meilleures techniques pour réussir vos entretiens.', 'video', 'guide', 'https://www.youtube.com/watch?v=exemple');

-- Créer un parcours
INSERT INTO parcours (objet, description) VALUES
('Reconversion vers les métiers du numérique', 'Un parcours complet pour vous orienter vers les métiers du développement web et de la data.');

-- Récupérer les IDs créés
SET @candidat_id = (SELECT id FROM user WHERE email = 'candidat@jobstep.com');
SET @parcours_id = (SELECT id FROM parcours WHERE objet = 'Reconversion vers les métiers du numérique');

-- Associer le candidat au parcours
INSERT INTO user_parcours (user_id, parcours_id) VALUES
(@candidat_id, @parcours_id);

-- Créer les étapes
INSERT INTO etape (descriptif, consignes, position, parcours_id) VALUES
('Bilan de compétences', 'Complétez le questionnaire de personnalité et le bilan de vos compétences actuelles.', 1, @parcours_id),
('Analyse des métiers cibles', 'Explorez les métiers du numérique qui correspondent à vos compétences et aspirations.', 2, @parcours_id),
('Préparation CV et candidatures', 'Adaptez votre CV aux métiers ciblés et préparez vos lettres de motivation.', 3, @parcours_id),
('Préparation aux entretiens', 'Entraînez-vous aux entretiens spécifiques aux métiers du numérique.', 4, @parcours_id);

-- Récupérer les IDs des ressources et étapes
SET @ressource1_id = (SELECT id FROM ressource WHERE intitule = 'Guide de rédaction CV');
SET @ressource2_id = (SELECT id FROM ressource WHERE intitule = 'Questionnaire de personnalité professionnelle');
SET @ressource3_id = (SELECT id FROM ressource WHERE intitule = 'Techniques d\'entretien');

SET @etape1_id = (SELECT id FROM etape WHERE descriptif = 'Bilan de compétences' AND parcours_id = @parcours_id);
SET @etape3_id = (SELECT id FROM etape WHERE descriptif = 'Préparation CV et candidatures' AND parcours_id = @parcours_id);
SET @etape4_id = (SELECT id FROM etape WHERE descriptif = 'Préparation aux entretiens' AND parcours_id = @parcours_id);

-- Associer les ressources aux étapes
INSERT INTO etape_ressource (etape_id, ressource_id) VALUES
(@etape1_id, @ressource2_id),
(@etape3_id, @ressource1_id),
(@etape4_id, @ressource3_id);

-- Créer des messages exemples entre candidat et conseiller
SET @conseiller_id = (SELECT id FROM user WHERE email = 'conseiller@jobstep.com');

INSERT INTO message (titre, contenu, date_heure, emetteur_id, receveur_id) VALUES
('Bienvenue dans votre parcours', 'Bonjour Jean,\n\nJe suis Sophie Martin, votre conseillère pour ce parcours de reconversion. N\'hésitez pas à me contacter si vous avez des questions.\n\nBien cordialement,\nSophie', NOW(), @conseiller_id, @candidat_id),
('Question sur le questionnaire', 'Bonjour Sophie,\n\nJ\'ai une question concernant le questionnaire de personnalité. Combien de temps faut-il prévoir pour le compléter ?\n\nMerci,\nJean', NOW(), @candidat_id, @conseiller_id);

-- Vérifier les données créées
SELECT 'Users créés:' as 'Info';
SELECT id, email, nom, prenom, role FROM user WHERE email IN ('conseiller@jobstep.com', 'candidat@jobstep.com');

SELECT '\nParcours créé:' as 'Info';
SELECT * FROM parcours;

SELECT '\nAssociations user-parcours:' as 'Info';
SELECT u.email, p.objet FROM user_parcours up 
JOIN user u ON u.id = up.user_id 
JOIN parcours p ON p.id = up.parcours_id;

SELECT '\nÉtapes créées:' as 'Info';
SELECT * FROM etape WHERE parcours_id = @parcours_id ORDER BY position;

SELECT '\nRessources créées:' as 'Info';
SELECT * FROM ressource;

SELECT '\nAssociations étape-ressource:' as 'Info';
SELECT e.descriptif, r.intitule FROM etape_ressource er
JOIN etape e ON e.id = er.etape_id
JOIN ressource r ON r.id = er.ressource_id;

SELECT '\nMessages créés:' as 'Info';
SELECT m.titre, u1.prenom as 'De', u2.prenom as 'À' FROM message m
JOIN user u1 ON m.emetteur_id = u1.id
JOIN user u2 ON m.receveur_id = u2.id;