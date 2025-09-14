<template>
  <div class="space-y-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Membres</h1>
        <p class="text-gray-600 mt-2">Gérez les membres de votre dahira/kourel</p>
      </div>
      <div class="flex items-center space-x-4">
        <button class="btn-modern" @click="ouvrirModalAjout">
          <PlusIcon class="w-5 h-5 mr-2" />
          Ajouter un membre
        </button>
      </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card-modern p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
          <input 
            v-model="filtres.recherche"
            type="text" 
            placeholder="Nom, prénom, email..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
          <select 
            v-model="filtres.statut"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les statuts</option>
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
            <option value="suspendu">Suspendu</option>
            <option value="ancien">Ancien</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
          <select 
            v-model="filtres.role"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Tous les rôles</option>
            <option value="admin">Administrateur</option>
            <option value="tresorier">Trésorier</option>
            <option value="responsable">Responsable</option>
            <option value="membre">Membre</option>
          </select>
        </div>
        <div class="flex items-end">
          <button 
            @click="reinitialiserFiltres"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Réinitialiser
          </button>
        </div>
      </div>
    </div>

    <!-- Table des membres -->
    <div class="card-modern overflow-hidden">
      <div class="overflow-x-auto">
        <table class="table-modern w-full">
          <thead>
            <tr>
              <th class="text-left">Membre</th>
              <th class="text-left">Contact</th>
              <th class="text-left">Statut</th>
              <th class="text-left">Rôle</th>
              <th class="text-left">Taux Présence</th>
              <th class="text-left">Cotisations</th>
              <th class="text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="membre in membresFiltres" :key="membre.id" class="hover:bg-gray-50">
              <td class="py-4">
                <div class="flex items-center space-x-3">
                  <img 
                    :src="membre.photo_url || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'" 
                    :alt="membre.nom_complet"
                    class="w-10 h-10 rounded-full"
                  >
                  <div>
                    <p class="font-medium text-gray-900">{{ membre.nom_complet }}</p>
                    <p class="text-sm text-gray-600">{{ membre.profession || 'Non spécifié' }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4">
                <div>
                  <p class="text-sm text-gray-900">{{ membre.email }}</p>
                  <p class="text-sm text-gray-600">{{ membre.telephone || 'Non renseigné' }}</p>
                </div>
              </td>
              <td class="py-4">
                <span :class="getStatutBadgeClass(membre.statut)" class="px-2 py-1 rounded-full text-xs font-medium">
                  {{ membre.statut }}
                </span>
              </td>
              <td class="py-4">
                <span class="text-sm text-gray-900">{{ membre.role?.nom || 'Membre' }}</span>
              </td>
              <td class="py-4">
                <div class="flex items-center space-x-2">
                  <div class="w-16 bg-gray-200 rounded-full h-2">
                    <div 
                      :class="getTauxPresenceColor(membre.taux_presence)"
                      class="h-2 rounded-full transition-all duration-300"
                      :style="{ width: `${membre.taux_presence}%` }"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-600">{{ membre.taux_presence }}%</span>
                </div>
              </td>
              <td class="py-4">
                <div class="text-sm">
                  <p class="text-gray-900">{{ membre.cotisations_payees }}/{{ membre.cotisations_totales }}</p>
                  <p class="text-gray-600">{{ formatCurrency(membre.montant_restant) }}</p>
                </div>
              </td>
              <td class="py-4">
                <div class="flex items-center justify-end space-x-2">
                  <button 
                    @click="voirProfil(membre)"
                    class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                    title="Voir le profil"
                  >
                    <EyeIcon class="w-4 h-4" />
                  </button>
                  <button 
                    @click="editerMembre(membre)"
                    class="p-2 text-gray-400 hover:text-green-600 transition-colors"
                    title="Éditer"
                  >
                    <PencilIcon class="w-4 h-4" />
                  </button>
                  <button 
                    @click="supprimerMembre(membre)"
                    class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                    title="Supprimer"
                  >
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Affichage de {{ membresFiltres.length }} sur {{ membres.length }} membres
          </div>
          <div class="flex items-center space-x-2">
            <button 
              @click="pagePrecedente"
              :disabled="pageCourante === 1"
              class="px-3 py-1 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Précédent
            </button>
            <span class="px-3 py-1 text-sm text-gray-700">
              Page {{ pageCourante }} sur {{ totalPages }}
            </span>
            <button 
              @click="pageSuivante"
              :disabled="pageCourante === totalPages"
              class="px-3 py-1 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Suivant
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal d'ajout/édition -->
    <div v-if="modalOuvert" class="modal-overlay" @click="fermerModal">
      <div class="modal-content" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
          {{ membreEnEdition ? 'Modifier le membre' : 'Ajouter un nouveau membre' }}
        </h3>
        
        <form @submit.prevent="sauvegarderMembre" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
              <input 
                v-model="formMembre.nom"
                type="text" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
              <input 
                v-model="formMembre.prenom"
                type="text" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input 
                v-model="formMembre.email"
                type="email" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
              <input 
                v-model="formMembre.telephone"
                type="tel" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
              <input 
                v-model="formMembre.profession"
                type="text" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
              <select 
                v-model="formMembre.statut"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="suspendu">Suspendu</option>
                <option value="ancien">Ancien</option>
              </select>
            </div>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-6">
            <button 
              type="button"
              @click="fermerModal"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="btn-modern"
            >
              {{ membreEnEdition ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  PlusIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

// État réactif
const modalOuvert = ref(false)
const membreEnEdition = ref(null)
const pageCourante = ref(1)
const membresParPage = 10

const filtres = ref({
  recherche: '',
  statut: '',
  role: ''
})

const formMembre = ref({
  nom: '',
  prenom: '',
  email: '',
  telephone: '',
  profession: '',
  statut: 'actif'
})

// Données simulées
const membres = ref([
  {
    id: 1,
    nom: 'Fall',
    prenom: 'Moussa',
    email: 'moussa.fall@email.com',
    telephone: '+221 77 123 45 67',
    profession: 'Ingénieur',
    statut: 'actif',
    role: { nom: 'Administrateur' },
    taux_presence: 95,
    cotisations_payees: 3,
    cotisations_totales: 3,
    montant_restant: 0,
    photo_url: null
  },
  {
    id: 2,
    nom: 'Diop',
    prenom: 'Aminata',
    email: 'aminata.diop@email.com',
    telephone: '+221 78 234 56 78',
    profession: 'Enseignante',
    statut: 'actif',
    role: { nom: 'Trésorier' },
    taux_presence: 87,
    cotisations_payees: 2,
    cotisations_totales: 3,
    montant_restant: 50000,
    photo_url: null
  },
  {
    id: 3,
    nom: 'Ba',
    prenom: 'Ibrahima',
    email: 'ibrahima.ba@email.com',
    telephone: '+221 76 345 67 89',
    profession: 'Médecin',
    statut: 'actif',
    role: { nom: 'Membre' },
    taux_presence: 78,
    cotisations_payees: 1,
    cotisations_totales: 3,
    montant_restant: 100000,
    photo_url: null
  }
])

// Computed properties
const membresFiltres = computed(() => {
  let resultat = membres.value

  // Filtre par recherche
  if (filtres.value.recherche) {
    const recherche = filtres.value.recherche.toLowerCase()
    resultat = resultat.filter(membre => 
      membre.nom.toLowerCase().includes(recherche) ||
      membre.prenom.toLowerCase().includes(recherche) ||
      membre.email.toLowerCase().includes(recherche)
    )
  }

  // Filtre par statut
  if (filtres.value.statut) {
    resultat = resultat.filter(membre => membre.statut === filtres.value.statut)
  }

  // Filtre par rôle
  if (filtres.value.role) {
    resultat = resultat.filter(membre => membre.role?.nom === filtres.value.role)
  }

  // Ajouter nom_complet
  resultat = resultat.map(membre => ({
    ...membre,
    nom_complet: `${membre.prenom} ${membre.nom}`
  }))

  return resultat
})

const totalPages = computed(() => {
  return Math.ceil(membresFiltres.value.length / membresParPage)
})

// Fonctions utilitaires
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    minimumFractionDigits: 0
  }).format(amount)
}

const getStatutBadgeClass = (statut) => {
  const classes = {
    'actif': 'bg-green-100 text-green-800',
    'inactif': 'bg-gray-100 text-gray-800',
    'suspendu': 'bg-yellow-100 text-yellow-800',
    'ancien': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getTauxPresenceColor = (taux) => {
  if (taux >= 90) return 'bg-green-500'
  if (taux >= 70) return 'bg-yellow-500'
  return 'bg-red-500'
}

// Fonctions de gestion
const ouvrirModalAjout = () => {
  membreEnEdition.value = null
  formMembre.value = {
    nom: '',
    prenom: '',
    email: '',
    telephone: '',
    profession: '',
    statut: 'actif'
  }
  modalOuvert.value = true
}

const editerMembre = (membre) => {
  membreEnEdition.value = membre
  formMembre.value = { ...membre }
  modalOuvert.value = true
}

const fermerModal = () => {
  modalOuvert.value = false
  membreEnEdition.value = null
}

const sauvegarderMembre = () => {
  if (membreEnEdition.value) {
    // Modification
    const index = membres.value.findIndex(m => m.id === membreEnEdition.value.id)
    if (index !== -1) {
      membres.value[index] = { ...membres.value[index], ...formMembre.value }
    }
  } else {
    // Ajout
    const nouveauMembre = {
      id: Date.now(),
      ...formMembre.value,
      role: { nom: 'Membre' },
      taux_presence: 0,
      cotisations_payees: 0,
      cotisations_totales: 0,
      montant_restant: 0,
      photo_url: null
    }
    membres.value.push(nouveauMembre)
  }
  
  fermerModal()
  window.addToast('Membre sauvegardé avec succès !', 'success')
}

const supprimerMembre = (membre) => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer ${membre.nom_complet} ?`)) {
    const index = membres.value.findIndex(m => m.id === membre.id)
    if (index !== -1) {
      membres.value.splice(index, 1)
      window.addToast('Membre supprimé avec succès !', 'success')
    }
  }
}

const voirProfil = (membre) => {
  window.addToast(`Profil de ${membre.nom_complet}`, 'info')
}

const reinitialiserFiltres = () => {
  filtres.value = {
    recherche: '',
    statut: '',
    role: ''
  }
}

const pagePrecedente = () => {
  if (pageCourante.value > 1) {
    pageCourante.value--
  }
}

const pageSuivante = () => {
  if (pageCourante.value < totalPages.value) {
    pageCourante.value++
  }
}

// Initialisation
onMounted(() => {
  console.log('Composant Membres initialisé')
})
</script>

