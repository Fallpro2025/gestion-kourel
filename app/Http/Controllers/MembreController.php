<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MembreController extends Controller
{
    /**
     * Afficher la liste des membres
     */
    public function index(Request $request)
    {
        $query = Membre::with(['role', 'assignationsCotisation', 'presences']);

        // Filtres
        if ($request->has('recherche') && $request->recherche) {
            $query->recherche($request->recherche);
        }

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('role_id') && $request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $membres = $query->paginate($perPage);

        // Ajouter des données calculées
        $membres->getCollection()->transform(function ($membre) {
            $membre->taux_presence = $membre->calculerTauxPresence();
            $membre->montant_cotisations_retard = $membre->getMontantCotisationsRetard();
            return $membre;
        });

        return response()->json([
            'membres' => $membres,
            'statistiques' => [
                'total' => Membre::count(),
                'actifs' => Membre::actifs()->count(),
                'inactifs' => Membre::where('statut', 'inactif')->count(),
                'suspendus' => Membre::where('statut', 'suspendu')->count(),
                'anciens' => Membre::where('statut', 'ancien')->count()
            ]
        ]);
    }

    /**
     * Afficher un membre spécifique
     */
    public function show(Membre $membre)
    {
        $membre->load(['role', 'assignationsCotisation.projet', 'presences.activite']);
        
        return response()->json([
            'membre' => [
                'id' => $membre->id,
                'nom' => $membre->nom,
                'prenom' => $membre->prenom,
                'nom_complet' => $membre->nom_complet,
                'email' => $membre->email,
                'telephone' => $membre->telephone,
                'date_naissance' => $membre->date_naissance,
                'date_adhesion' => $membre->date_adhesion,
                'statut' => $membre->statut,
                'role' => $membre->role,
                'photo_url' => $membre->photo_url,
                'adresse' => $membre->adresse,
                'profession' => $membre->profession,
                'niveau_etude' => $membre->niveau_etude,
                'competences' => $membre->competences,
                'disponibilites' => $membre->disponibilites,
                'preferences_notifications' => $membre->preferences_notifications,
                'age' => $membre->age,
                'taux_presence' => $membre->calculerTauxPresence(),
                'montant_cotisations_retard' => $membre->getMontantCotisationsRetard(),
                'assignations_cotisation' => $membre->assignationsCotisation,
                'presences_recentes' => $membre->presences()->with('activite')->latest()->limit(10)->get(),
                'activites_responsable' => $membre->activitesResponsable,
                'evenements_crees' => $membre->evenementsCrees,
                'alertes' => $membre->alertes()->latest()->limit(5)->get()
            ]
        ]);
    }

    /**
     * Créer un nouveau membre
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:membres,email',
            'telephone' => 'nullable|string|max:20|unique:membres,telephone',
            'date_naissance' => 'nullable|date|before:today',
            'date_adhesion' => 'nullable|date',
            'statut' => 'nullable|in:actif,inactif,suspendu,ancien',
            'role_id' => 'nullable|exists:roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'adresse' => 'nullable|string',
            'profession' => 'nullable|string|max:100',
            'niveau_etude' => 'nullable|string|max:50',
            'competences' => 'nullable|array',
            'disponibilites' => 'nullable|array',
            'preferences_notifications' => 'nullable|array',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only([
            'nom', 'prenom', 'email', 'telephone', 'date_naissance',
            'date_adhesion', 'statut', 'role_id', 'adresse', 'profession',
            'niveau_etude', 'competences', 'disponibilites', 'preferences_notifications'
        ]);

        // Gérer l'upload de photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('membres/photos', 'public');
            $data['photo_url'] = Storage::url($photoPath);
        }

        // Définir les valeurs par défaut
        $data['date_adhesion'] = $data['date_adhesion'] ?? now()->toDateString();
        $data['statut'] = $data['statut'] ?? 'actif';
        $data['password'] = Hash::make($request->password);

        $membre = Membre::create($data);
        $membre->load('role');

        return response()->json([
            'message' => 'Membre créé avec succès',
            'membre' => $membre
        ], 201);
    }

    /**
     * Mettre à jour un membre
     */
    public function update(Request $request, Membre $membre)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:100',
            'prenom' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:membres,email,' . $membre->id,
            'telephone' => 'nullable|string|max:20|unique:membres,telephone,' . $membre->id,
            'date_naissance' => 'nullable|date|before:today',
            'date_adhesion' => 'nullable|date',
            'statut' => 'nullable|in:actif,inactif,suspendu,ancien',
            'role_id' => 'nullable|exists:roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'adresse' => 'nullable|string',
            'profession' => 'nullable|string|max:100',
            'niveau_etude' => 'nullable|string|max:50',
            'competences' => 'nullable|array',
            'disponibilites' => 'nullable|array',
            'preferences_notifications' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only([
            'nom', 'prenom', 'email', 'telephone', 'date_naissance',
            'date_adhesion', 'statut', 'role_id', 'adresse', 'profession',
            'niveau_etude', 'competences', 'disponibilites', 'preferences_notifications'
        ]);

        // Gérer l'upload de photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($membre->photo_url) {
                $oldPhotoPath = str_replace('/storage/', '', $membre->photo_url);
                Storage::disk('public')->delete($oldPhotoPath);
            }

            $photoPath = $request->file('photo')->store('membres/photos', 'public');
            $data['photo_url'] = Storage::url($photoPath);
        }

        $membre->update($data);
        $membre->load('role');

        return response()->json([
            'message' => 'Membre mis à jour avec succès',
            'membre' => $membre
        ]);
    }

    /**
     * Supprimer un membre
     */
    public function destroy(Membre $membre)
    {
        // Vérifier si le membre peut être supprimé
        if ($membre->presences()->count() > 0 || $membre->assignationsCotisation()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer ce membre car il a des données associées',
                'errors' => ['membre' => ['Le membre a des activités ou cotisations associées']]
            ], 422);
        }

        // Supprimer la photo si elle existe
        if ($membre->photo_url) {
            $photoPath = str_replace('/storage/', '', $membre->photo_url);
            Storage::disk('public')->delete($photoPath);
        }

        $membre->delete();

        return response()->json([
            'message' => 'Membre supprimé avec succès'
        ]);
    }

    /**
     * Obtenir les statistiques d'un membre
     */
    public function statistiques(Membre $membre)
    {
        $statistiques = [
            'taux_presence' => $membre->calculerTauxPresence(),
            'total_activites' => $membre->presences()->count(),
            'activites_presentes' => $membre->presences()->where('statut', 'present')->count(),
            'absences_justifiees' => $membre->presences()->where('statut', 'absent_justifie')->count(),
            'absences_non_justifiees' => $membre->presences()->where('statut', 'absent_non_justifie')->count(),
            'retards' => $membre->presences()->where('statut', 'retard')->count(),
            'montant_cotisations_total' => $membre->assignationsCotisation()->sum('montant_assigné'),
            'montant_cotisations_paye' => $membre->assignationsCotisation()->sum('montant_payé'),
            'montant_cotisations_retard' => $membre->getMontantCotisationsRetard(),
            'cotisations_en_retard' => $membre->assignationsCotisation()->enRetard()->count(),
            'alertes_actives' => $membre->alertes()->nonResolues()->count(),
            'activites_responsable' => $membre->activitesResponsable()->count(),
            'evenements_crees' => $membre->evenementsCrees()->count()
        ];

        return response()->json([
            'statistiques' => $statistiques
        ]);
    }

    /**
     * Obtenir l'historique d'un membre
     */
    public function historique(Membre $membre, Request $request)
    {
        $periode = $request->get('periode', '30'); // jours
        $dateDebut = now()->subDays($periode);

        $historique = [
            'presences' => $membre->presences()
                ->with('activite')
                ->where('created_at', '>=', $dateDebut)
                ->orderBy('created_at', 'desc')
                ->get(),
            'cotisations' => $membre->assignationsCotisation()
                ->with('projet')
                ->where('created_at', '>=', $dateDebut)
                ->orderBy('created_at', 'desc')
                ->get(),
            'alertes' => $membre->alertes()
                ->where('created_at', '>=', $dateDebut)
                ->orderBy('created_at', 'desc')
                ->get()
        ];

        return response()->json([
            'historique' => $historique,
            'periode' => $periode,
            'date_debut' => $dateDebut->toDateString()
        ]);
    }

    /**
     * Exporter les données d'un membre
     */
    public function export(Membre $membre, Request $request)
    {
        $format = $request->get('format', 'pdf'); // pdf ou excel
        
        // TODO: Implémenter l'export PDF/Excel
        // Pour l'instant, retourner les données JSON
        
        $membre->load(['role', 'assignationsCotisation.projet', 'presences.activite', 'alertes']);
        
        return response()->json([
            'membre' => $membre,
            'statistiques' => [
                'taux_presence' => $membre->calculerTauxPresence(),
                'montant_cotisations_retard' => $membre->getMontantCotisationsRetard()
            ],
            'export_format' => $format,
            'exported_at' => now()->toISOString()
        ]);
    }

    /**
     * Rechercher des membres
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'membres' => [],
                'message' => 'La recherche doit contenir au moins 2 caractères'
            ]);
        }

        $membres = Membre::recherche($query)
            ->with('role')
            ->limit(10)
            ->get();

        return response()->json([
            'membres' => $membres,
            'query' => $query
        ]);
    }
}
