# 📱 CAHIER DES CHARGES - Application Mobile Bwatoo

> **Spécifications techniques complètes pour l'application mobile iOS/Android**
> 
> 📅 Version 1.0 - 6 juillet 2025  
> 🔗 Liens : [[README]] | [[META]] | [[TODO]] | [[OPTIMISATION-PREMIUM-ADS]]

---

## 🎯 Résumé Exécutif

### Vision Produit
Développer une **application mobile native** iOS/Android qui transforme l'expérience Bwatoo en offrant toutes les fonctionnalités de la plateforme web avec des optimisations mobiles spécifiques au marché africain : géolocalisation précise, support hors-ligne, paiements mobiles, et notifications intelligentes.

### Objectifs Stratégiques
1. **Adoption massive** : 80% des utilisateurs sur mobile d'ici 12 mois
2. **Engagement supérieur** : +150% temps passé vs web
3. **Monétisation mobile** : 60% revenus Premium Ads via app
4. **Expansion géographique** : 20+ pays africains accessibles
5. **Experience différenciante** : App store ratings 4.5+ étoiles

---

## 1️⃣ Analyse de Marché et Contexte

### 📊 Réalités du Marché Africain

#### Pénétration Mobile
- **1.2 milliard** d'utilisateurs mobiles en Afrique
- **85%** utilisent smartphones comme device principal
- **65%** accèdent internet exclusivement via mobile
- **70%** des transactions e-commerce sur mobile

#### Contraintes Techniques
- **Connexions intermittentes** : 2G/3G dominant en rural
- **Devices variés** : Android entry-level majoritaire
- **Storage limité** : 16-64GB typique
- **Battery anxiety** : Autonomie critique

#### Opportunités
- **Mobile Money** : 300M+ comptes actifs
- **WhatsApp** : 90%+ taux adoption
- **Voice/Audio** : Préféré pour analphabètes
- **Offline-first** : Nécessité absolue

### 🎯 Positionnement Concurrentiel

#### Avantages Différenciants
1. **Premium Ads natifs** : Monétisation intégrée seamless
2. **Multi-pays unifiés** : Une app pour toute l'Afrique
3. **Offline-first** : Fonctionne sans connexion
4. **Local payments** : Mobile Money natif
5. **AI-powered** : Recommandations intelligentes

---

## 2️⃣ Architecture Technique

### 🏗️ Stack Technologique Recommandé

#### **Option A : React Native (RECOMMANDÉE)**

##### Justification
```
✅ Avantages                          ❌ Inconvénients
- Code partagé iOS/Android 85%        - Performance native -10%
- Team web peut contribuer             - Dépendance à Facebook
- Hot reload développement rapide      - Bridge JS overhead
- Large écosystème packages           - Debug complexe parfois
- Budget optimisé (1 équipe)          - Updates RN fréquentes
```

##### Stack Détaillé
```javascript
// Core Framework
React Native 0.73+
TypeScript 5.0+
Metro bundler

// Navigation
React Navigation 6
React Navigation Material Top Tabs

// State Management
Redux Toolkit + RTK Query
Redux Persist (offline)
React Query (server state)

// UI Framework
NativeBase 3.4+ (accessible, theme-ready)
React Native Paper (Material Design)
Lottie React Native (animations)

// Network & API
Axios + interceptors
React Native NetInfo
React Native Offline Queue

// Storage
AsyncStorage (settings)
MMKV (high performance)
SQLite (offline data)
React Native Keychain (secure)

// Features
React Native Maps (géolocalisation)
React Native Image Picker
React Native Push Notifications
React Native In-App Purchase
React Native Biometrics
```

#### **Option B : Flutter (ALTERNATIVE)**

##### Justification
```
✅ Avantages                          ❌ Inconvénients
- Performance quasi-native             - Dart language learning curve
- UI consistante cross-platform        - Team web inexperience
- Hot reload excellent                 - Package ecosystem plus petit
- Backed by Google                     - Size app plus important
- Fuchsia future-proof                 - Community moins mature
```

#### **Option C : Native (NON RECOMMANDÉE)**

##### Justification
```
✅ Avantages                          ❌ Inconvénients
- Performance maximale                 - 2 équipes distinctes (iOS/Android)
- Accès APIs plateforme complètes      - Budget x2, timeline x2
- Platform-specific UX                 - Maintenance complexe
- App store optimization              - Features décalées
```

### 🎯 **Décision Finale : React Native**

#### Critères de Sélection
1. **Budget optimisé** : 60% économies vs native
2. **Time-to-market** : 50% plus rapide
3. **Team efficiency** : Équipe web existante
4. **Maintenance** : Une codebase unifiée
5. **African constraints** : Performance acceptable sur low-end devices

### 🔗 Architecture API

#### RESTful API Extensions
```
Nouveaux endpoints mobiles :

POST /api/v1/mobile/auth/phone          # Auth via numéro téléphone
GET  /api/v1/mobile/posts/nearby        # Posts géolocalisés
POST /api/v1/mobile/posts/voice-search  # Recherche vocale
GET  /api/v1/mobile/sync/delta          # Sync différentielle
POST /api/v1/mobile/payments/momo       # Mobile Money
GET  /api/v1/mobile/promotions/suggest  # Suggestions IA
POST /api/v1/mobile/analytics/events    # Tracking mobile
```

#### Offline-First Strategy
```javascript
// Architecture 3-layer
┌─────────────────┐
│   Presentation  │ ← React Native Components
├─────────────────┤
│   Domain Logic  │ ← Redux Store + Business Logic
├─────────────────┤
│   Data Sources  │ ← API + SQLite + AsyncStorage
└─────────────────┘

// Sync Strategy
1. Write operations → Local SQLite first
2. Background sync when online
3. Conflict resolution server-side
4. Optimistic UI updates
```

---

## 3️⃣ Fonctionnalités Détaillées

### 🏠 **Module 1 : Onboarding & Authentication**

#### Features Principales
- **Quick Tour** : 3-screen introduction value proposition
- **Phone Auth** : OTP via SMS (pas email obligatoire)
- **Social Login** : Google, Facebook, Apple (iOS)
- **Biometric Auth** : TouchID/FaceID/Fingerprint
- **Guest Mode** : Navigation sans inscription

#### Spécificités Africaines
```
📱 Phone Number First
- Input avec code pays automatique
- Validation format local
- SMS gratuit via partnerships opérateurs

🌍 Multi-Country Setup
- Sélection pays au premier lancement
- Auto-détection via IP/GPS
- Langue interface adaptée

🎯 Progressive Registration
- Étape 1: Numéro + nom
- Étape 2: Photo profil (optionnel)
- Étape 3: Localisation précise
- Étape 4: Préférences notifications
```

### 📋 **Module 2 : Home & Discovery**

#### Features Core
- **Smart Feed** : Annonces personnalisées algorithme ML
- **Category Quick Access** : 8 catégories principales
- **Search Bar** : Auto-completion, filters, voice search
- **Location Aware** : Posts à proximité prioritaires
- **Premium Showcase** : Zone dédiée promotions payantes

#### Innovations Mobile
```
🎤 Voice Search
- Recognition vocale multilingue (FR/EN/local)
- "Cherche maison 3 chambres Abidjan"
- Auto-convert to structured search

📍 Hyper-Local Discovery
- Rayon recherche ajustable (500m → 50km)
- Map view intégrée
- "Posts autour de moi"
- Neighbourhood-based filtering

🤖 AI Recommendations
- Based on views, saves, contacts
- "Vous pourriez aimer"
- Trending in your area
- Price drop alerts
```

### 🔍 **Module 3 : Search & Filters**

#### Architecture
```
┌─────────────────┐
│  Search Input   │ ← Voice + Text + Visual
├─────────────────┤
│  Quick Filters  │ ← Prix, Distance, Catégorie
├─────────────────┤
│  Advanced UI    │ ← Drawer avec tous filtres
├─────────────────┤
│  Results Grid   │ ← Infinite scroll + Pull refresh
└─────────────────┘
```

#### Features Avancées
- **Visual Search** : Photo → Similar products
- **Saved Searches** : Alerts notifications automatiques
- **Search History** : Quick re-search dernières requêtes
- **Smart Suggestions** : "Peut-être cherchiez-vous..."
- **Filter Presets** : "Urgent", "Proche", "Pas cher"

### 📝 **Module 4 : Post Creation & Management**

#### Workflow Optimisé Mobile
```
Étape 1: Category Selection
- Visual grid avec icônes
- Search catégorie
- Recent/Popular categories

Étape 2: Photo Capture
- Multi-shot camera native
- Gallery selection multiple
- Auto-compress & optimize
- Crop/rotate basic editing

Étape 3: Details Form
- Smart form progressif
- Auto-complete location
- Price suggestions based on similar
- Voice-to-text description

Étape 4: Location & Visibility
- Map picker précis
- Private/Public toggle
- Promotion upgrade prompt

Étape 5: Review & Publish
- Preview final
- Terms acceptance
- Social share options
```

#### Gestion Posts
- **My Listings** : Grid view avec stats
- **Quick Actions** : Swipe edit/promote/delete
- **Performance Insights** : Views, contacts, saves
- **Repost Feature** : One-tap republish expired
- **Bulk Operations** : Select multiple → Actions

### 💎 **Module 5 : Premium Ads (Mobile-Optimized)**

#### Purchase Flow Simplifié
```
Post Detail → "Boost" CTA → Package Selection → Payment → Confirmation

🥉 Bronze Package (5 crédits)
- Bump to top for 3 days
- +50% visibility estimated
- Mobile Money 1000 CFA

🥈 Silver Package (15 crédits)  
- Featured badge 7 days
- Homepage placement
- +200% visibility estimated
- Mobile Money 3000 CFA

🥇 Gold Package (30 crédits)
- All features 14 days
- Priority in all searches
- +500% visibility estimated
- Mobile Money 6000 CFA
```

#### Mobile Money Integration
```javascript
// Providers Support
orangeMoney: {
  countries: ['CI', 'SN', 'ML', 'BF', 'CM'],
  currencies: ['XOF', 'XAF'],
  minAmount: 100,
  maxAmount: 150000
},
mtnMoMo: {
  countries: ['GH', 'UG', 'RW', 'CM', 'CI'],
  currencies: ['GHS', 'UGX', 'RWF', 'XAF', 'XOF'],
  minAmount: 1,
  maxAmount: 5000000
},
airtelMoney: {
  countries: ['KE', 'TZ', 'UG', 'RW', 'MG'],
  currencies: ['KES', 'TZS', 'UGX', 'RWF', 'MGA'],
  minAmount: 10,
  maxAmount: 1000000
}
```

### 💬 **Module 6 : Messaging & Communication**

#### Features
- **In-app Chat** : Thread par annonce
- **Voice Messages** : Record/play inline
- **Photo Sharing** : Quick camera access
- **WhatsApp Bridge** : Redirect to WhatsApp chat
- **Call Integration** : Direct phone call CTA
- **Safety Features** : Block, report, safe meeting tips

#### Spécificités Mobile
```
📱 Native Integration
- Phone call direct depuis chat
- WhatsApp handoff seamless
- SMS fallback si app offline

🔔 Smart Notifications
- Push intelligentes (pas spam)
- Sound/vibration customizable
- Quick reply depuis notification
- Chat bubbles (Android 11+)

🛡️ Trust & Safety
- Profile verification badges
- Meeting in public reminders
- Emergency contact sharing
- Auto-block suspicious patterns
```

### 👤 **Module 7 : Profile & Account**

#### User Profile
- **Photo Profile** : Camera native + crop
- **Verification Badges** : Phone, email, ID (future)
- **Seller Stats** : Rating, reviews, response time
- **Social Proof** : Testimonials, success stories
- **Preferences** : Notifications, privacy, language

#### Account Management
- **My Activity** : Posts, messages, favorites, searches
- **Subscription Status** : Credits balance, plan details
- **Payment History** : Receipts mobile money
- **Settings** : Theme, language, privacy, storage
- **Help Center** : FAQs, tutorials, contact support

---

## 4️⃣ Architecture Technique Détaillée

### 📱 Structure de l'App

```
src/
├── components/           # Composants UI réutilisables
│   ├── forms/           # Formulaires optimisés mobile
│   ├── lists/           # Lists avec optimisations performance
│   ├── maps/            # Composants géolocalisation
│   └── media/           # Image/video players
├── screens/             # Écrans applicatifs
│   ├── auth/            # Authentication flows
│   ├── home/            # Discovery & feed
│   ├── search/          # Search & filters
│   ├── post/            # CRUD posts
│   ├── chat/            # Messaging
│   └── profile/         # User management
├── services/            # Logique métier
│   ├── api/             # API calls & caching
│   ├── auth/            # Authentication service
│   ├── location/        # GPS & geolocation
│   ├── payments/        # Mobile money integration
│   ├── sync/            # Offline sync logic
│   └── analytics/       # Tracking events
├── store/               # Redux state management
│   ├── slices/          # Feature-based slices
│   ├── middleware/      # Custom middleware
│   └── selectors/       # Memoized selectors
├── utils/               # Utilitaires
│   ├── constants/       # App constants
│   ├── helpers/         # Helper functions
│   ├── hooks/           # Custom React hooks
│   └── validators/      # Form validation
└── assets/              # Images, fonts, etc.
```

### 🔄 State Management Strategy

```javascript
// Redux Toolkit Structure
store/
├── auth/
│   ├── authSlice.js     # User authentication state
│   ├── authAPI.js       # Auth API calls
│   └── authSelectors.js # Memoized auth selectors
├── posts/
│   ├── postsSlice.js    # Posts CRUD state
│   ├── postsAPI.js      # Posts API with caching
│   ├── offlineQueue.js  # Offline operations queue
│   └── postsSelectors.js
├── chat/
│   ├── chatSlice.js     # Real-time messaging
│   ├── chatAPI.js       # WebSocket integration
│   └── chatSelectors.js
└── premiumAds/
    ├── premiumSlice.js  # Premium features state
    ├── premiumAPI.js    # Promotion purchases
    └── premiumSelectors.js
```

### 📡 Network & Sync Architecture

#### Offline-First Strategy
```javascript
// Network-aware data fetching
const useNetworkAwareQuery = (queryKey, queryFn, options = {}) => {
  const isOnline = useNetworkState();
  
  return useQuery({
    queryKey,
    queryFn,
    enabled: isOnline || options.offlineEnabled,
    staleTime: isOnline ? 5 * 60 * 1000 : Infinity,
    cacheTime: 24 * 60 * 60 * 1000, // 24h cache offline
    retry: isOnline ? 3 : 0,
    ...options
  });
};

// Sync queue for offline actions
class OfflineSyncQueue {
  constructor() {
    this.queue = [];
    this.isProcessing = false;
  }
  
  addAction(action) {
    // Add to SQLite queue
    this.queue.push({
      id: uuid(),
      action,
      timestamp: Date.now(),
      retries: 0
    });
  }
  
  async processQueue() {
    if (!isOnline() || this.isProcessing) return;
    
    this.isProcessing = true;
    
    for (const item of this.queue) {
      try {
        await this.executeAction(item.action);
        this.removeFromQueue(item.id);
      } catch (error) {
        this.handleFailure(item, error);
      }
    }
    
    this.isProcessing = false;
  }
}
```

### 🔐 Security Architecture

#### Authentication & Authorization
```javascript
// Multi-factor authentication flow
const AuthService = {
  // Phone number verification
  async sendOTP(phoneNumber, countryCode) {
    const response = await api.post('/auth/send-otp', {
      phone: `${countryCode}${phoneNumber}`,
      method: 'sms' // ou 'whatsapp' si disponible
    });
    return response.data;
  },
  
  // OTP verification with biometric storage
  async verifyOTP(phoneNumber, otpCode) {
    const response = await api.post('/auth/verify-otp', {
      phone: phoneNumber,
      otp: otpCode
    });
    
    if (response.data.success) {
      // Store tokens securely
      await Keychain.setInternetCredentials(
        'bwatoo_tokens',
        response.data.user.id,
        JSON.stringify({
          accessToken: response.data.accessToken,
          refreshToken: response.data.refreshToken
        })
      );
      
      // Enable biometric for future logins
      await this.enableBiometricAuth();
    }
    
    return response.data;
  },
  
  // Biometric authentication
  async authenticateWithBiometric() {
    const biometricAuth = await TouchID.authenticate(
      'Utilisez votre empreinte pour vous connecter',
      {
        fallbackLabel: 'Utiliser le code PIN',
        cancelLabel: 'Annuler'
      }
    );
    
    if (biometricAuth) {
      const credentials = await Keychain.getInternetCredentials('bwatoo_tokens');
      return JSON.parse(credentials.password);
    }
    
    throw new Error('Biometric authentication failed');
  }
};
```

#### Data Encryption
```javascript
// Sensitive data encryption
import CryptoJS from 'crypto-js';

class SecureStorage {
  constructor() {
    this.secretKey = 'user-device-specific-key'; // Generated per device
  }
  
  encrypt(data) {
    return CryptoJS.AES.encrypt(JSON.stringify(data), this.secretKey).toString();
  }
  
  decrypt(encryptedData) {
    const bytes = CryptoJS.AES.decrypt(encryptedData, this.secretKey);
    return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
  }
  
  // Store sensitive user data
  async storeSecure(key, data) {
    const encrypted = this.encrypt(data);
    await AsyncStorage.setItem(`secure_${key}`, encrypted);
  }
  
  // Retrieve sensitive data
  async getSecure(key) {
    const encrypted = await AsyncStorage.getItem(`secure_${key}`);
    return encrypted ? this.decrypt(encrypted) : null;
  }
}
```

---

## 5️⃣ Intégrations et Communication

### 📡 API Communication Strategy

#### REST API Adaptations
```javascript
// Mobile-optimized API client
class MobileAPIClient {
  constructor() {
    this.baseURL = 'https://api.bwatoo.fr/v1';
    this.timeoutMs = 10000; // 10s timeout mobile networks
    this.retryCount = 3;
    
    this.client = axios.create({
      baseURL: this.baseURL,
      timeout: this.timeoutMs,
      headers: {
        'User-Agent': 'BwatooMobile/1.0',
        'Accept-Encoding': 'gzip', // Compression importante
        'Cache-Control': 'max-age=300' // 5min cache default
      }
    });
    
    this.setupInterceptors();
  }
  
  setupInterceptors() {
    // Request interceptor: Add auth + network optimization
    this.client.interceptors.request.use(
      async (config) => {
        // Add auth token
        const token = await this.getStoredToken();
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        
        // Add device info
        config.headers['X-Device-Info'] = await this.getDeviceInfo();
        
        // Network-aware optimization
        const networkState = await NetInfo.fetch();
        if (networkState.type === 'cellular') {
          config.params = {
            ...config.params,
            compress: true, // Request compressed responses
            mobile_optimized: true
          };
        }
        
        return config;
      },
      (error) => Promise.reject(error)
    );
    
    // Response interceptor: Handle auth + cache
    this.client.interceptors.response.use(
      (response) => {
        // Cache successful responses
        this.cacheResponse(response);
        return response;
      },
      async (error) => {
        // Auto refresh token
        if (error.response?.status === 401) {
          const newToken = await this.refreshToken();
          if (newToken) {
            error.config.headers.Authorization = `Bearer ${newToken}`;
            return this.client.request(error.config);
          }
        }
        
        // Offline fallback
        if (!error.response && error.code === 'NETWORK_ERROR') {
          const cachedResponse = await this.getCachedResponse(error.config);
          if (cachedResponse) {
            return cachedResponse;
          }
        }
        
        return Promise.reject(error);
      }
    );
  }
}
```

#### Real-time Communication
```javascript
// WebSocket for real-time features
class RealtimeService {
  constructor() {
    this.ws = null;
    this.reconnectAttempts = 0;
    this.maxReconnectAttempts = 5;
    this.reconnectDelay = 1000;
  }
  
  connect(userId) {
    const wsUrl = `wss://api.bwatoo.fr/ws?user_id=${userId}`;
    
    this.ws = new WebSocket(wsUrl);
    
    this.ws.onopen = () => {
      console.log('WebSocket connected');
      this.reconnectAttempts = 0;
      
      // Subscribe to user-specific channels
      this.subscribe('user_notifications');
      this.subscribe('chat_messages');
      this.subscribe('post_updates');
    };
    
    this.ws.onmessage = (event) => {
      const data = JSON.parse(event.data);
      this.handleMessage(data);
    };
    
    this.ws.onclose = () => {
      console.log('WebSocket disconnected');
      this.attemptReconnect();
    };
    
    this.ws.onerror = (error) => {
      console.error('WebSocket error:', error);
    };
  }
  
  handleMessage(data) {
    switch (data.type) {
      case 'new_message':
        store.dispatch(addNewMessage(data.payload));
        this.showNotification(data.payload);
        break;
        
      case 'post_update':
        store.dispatch(updatePost(data.payload));
        break;
        
      case 'promotion_expired':
        store.dispatch(expirePromotion(data.payload));
        this.showNotification({
          title: 'Promotion expirée',
          body: 'Votre promotion a expiré. Renouvelez maintenant!'
        });
        break;
    }
  }
}
```

### 💳 Mobile Money Integration

#### Provider Abstraction Layer
```javascript
// Unified mobile money interface
class MobileMoneyService {
  constructor() {
    this.providers = {
      orange: new OrangeMoneyProvider(),
      mtn: new MTNMoMoProvider(),
      airtel: new AirtelMoneyProvider(),
      moov: new MoovMoneyProvider()
    };
  }
  
  // Auto-detect provider from phone number
  detectProvider(phoneNumber, countryCode) {
    const providers = {
      'CI': {
        'orange': /^(07|08|09)/,
        'mtn': /^(05|06)/,
        'moov': /^(01|02|03)/
      },
      'SN': {
        'orange': /^(77|78)/,
        'tigo': /^(76)/,
        'expresso': /^(70)/
      }
      // ... autres pays
    };
    
    const countryProviders = providers[countryCode];
    if (!countryProviders) return null;
    
    for (const [provider, regex] of Object.entries(countryProviders)) {
      if (regex.test(phoneNumber)) {
        return provider;
      }
    }
    
    return null;
  }
  
  // Process payment with detected provider
  async processPayment(amount, currency, phoneNumber, countryCode) {
    const provider = this.detectProvider(phoneNumber, countryCode);
    
    if (!provider || !this.providers[provider]) {
      throw new Error(`Provider not supported for ${phoneNumber}`);
    }
    
    const paymentData = {
      amount,
      currency,
      phoneNumber,
      countryCode,
      reference: this.generateReference(),
      callbackUrl: 'https://api.bwatoo.fr/payments/callback'
    };
    
    return await this.providers[provider].initiatePayment(paymentData);
  }
}

// Orange Money implementation
class OrangeMoneyProvider {
  constructor() {
    this.baseURL = 'https://api.orange.com/orange-money-webpay/dev/v1';
    this.credentials = {
      merchant_key: process.env.ORANGE_MERCHANT_KEY,
      merchant_secret: process.env.ORANGE_MERCHANT_SECRET
    };
  }
  
  async initiatePayment(paymentData) {
    const token = await this.getAccessToken();
    
    const response = await fetch(`${this.baseURL}/webpayment`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        merchant_key: this.credentials.merchant_key,
        currency: paymentData.currency,
        order_id: paymentData.reference,
        amount: paymentData.amount,
        return_url: paymentData.callbackUrl,
        cancel_url: paymentData.callbackUrl,
        notif_url: paymentData.callbackUrl,
        lang: 'fr',
        reference: `Bwatoo-${paymentData.reference}`
      })
    });
    
    const result = await response.json();
    
    return {
      success: true,
      paymentUrl: result.payment_url,
      reference: paymentData.reference,
      provider: 'orange'
    };
  }
}
```

### 🔔 Push Notifications Strategy

#### Smart Notification System
```javascript
// Intelligent notification manager
class NotificationManager {
  constructor() {
    this.channels = {
      'chat': {
        id: 'chat_messages',
        name: 'Messages',
        importance: 'high',
        sound: true,
        vibration: [200, 100, 200]
      },
      'promotions': {
        id: 'promotions',
        name: 'Promotions',
        importance: 'default',
        sound: false,
        vibration: [100]
      },
      'posts': {
        id: 'post_updates',
        name: 'Mes Annonces',
        importance: 'default',
        sound: true,
        vibration: [100, 50, 100]
      }
    };
    
    this.setupChannels();
  }
  
  // Machine learning pour optimiser timing
  async getOptimalSendTime(userId, notificationType) {
    const userBehavior = await this.getUserEngagementPattern(userId);
    const currentHour = new Date().getHours();
    
    // Éviter les heures de sommeil et prière
    const avoidHours = [0, 1, 2, 3, 4, 5, 12, 18]; // Nuit + prières
    
    if (avoidHours.includes(currentHour)) {
      // Planifier pour l'heure optimale
      const optimalHour = userBehavior.mostActiveHour || 9;
      return this.scheduleForLater(optimalHour);
    }
    
    return 'immediate';
  }
  
  // Grouping intelligent pour éviter spam
  async sendSmartNotification(notification) {
    const pending = await this.getPendingNotifications(notification.userId);
    
    if (pending.length > 2) {
      // Grouper en une notification digest
      return this.sendDigestNotification(pending.concat(notification));
    }
    
    const timing = await this.getOptimalSendTime(
      notification.userId, 
      notification.type
    );
    
    if (timing === 'immediate') {
      return this.sendImmediately(notification);
    } else {
      return this.scheduleNotification(notification, timing);
    }
  }
}
```

---

## 6️⃣ Performance et Optimisations

### ⚡ Performance Strategy

#### Bundle Optimization
```javascript
// Code splitting par feature
const LazyScreens = {
  Home: lazy(() => import('../screens/Home')),
  Search: lazy(() => import('../screens/Search')),
  PostCreate: lazy(() => import('../screens/PostCreate')),
  Chat: lazy(() => import('../screens/Chat')),
  Profile: lazy(() => import('../screens/Profile')),
  
  // Premium features loaded on demand
  PremiumAds: lazy(() => import('../screens/PremiumAds')),
  Analytics: lazy(() => import('../screens/Analytics'))
};

// Asset optimization
const ImageOptimizer = {
  // Lazy loading avec placeholders
  LazyImage: ({ source, style, ...props }) => {
    const [loaded, setLoaded] = useState(false);
    const [error, setError] = useState(false);
    
    return (
      <View style={style}>
        {!loaded && !error && (
          <Skeleton height={style.height} width={style.width} />
        )}
        <Image
          source={source}
          style={[style, { opacity: loaded ? 1 : 0 }]}
          onLoad={() => setLoaded(true)}
          onError={() => setError(true)}
          {...props}
        />
      </View>
    );
  },
  
  // Auto-resize selon device
  getOptimizedSource: (originalUrl, targetWidth) => {
    const devicePixelRatio = PixelRatio.get();
    const scaledWidth = targetWidth * devicePixelRatio;
    
    return `${originalUrl}?w=${scaledWidth}&q=85&fm=webp`;
  }
};
```

#### Memory Management
```javascript
// Liste virtualisée pour performance
import { FlashList } from '@shopify/flash-list';

const PostsList = ({ posts, onEndReached }) => {
  const renderPost = useCallback(({ item }) => (
    <PostCard post={item} />
  ), []);
  
  const getItemType = useCallback((item) => {
    return item.is_promoted ? 'promoted' : 'regular';
  }, []);
  
  return (
    <FlashList
      data={posts}
      renderItem={renderPost}
      getItemType={getItemType}
      estimatedItemSize={120}
      onEndReached={onEndReached}
      onEndReachedThreshold={0.7}
      keyExtractor={(item) => item.id.toString()}
      removeClippedSubviews={true}
      maxToRenderPerBatch={10}
      updateCellsBatchingPeriod={50}
    />
  );
};

// Image caching strategy
const ImageCacheManager = {
  maxCacheSize: 100 * 1024 * 1024, // 100MB
  cacheDirectory: `${RNFS.CachesDirectoryPath}/images`,
  
  async cacheImage(url) {
    const filename = this.getFilenameFromUrl(url);
    const filepath = `${this.cacheDirectory}/${filename}`;
    
    try {
      const exists = await RNFS.exists(filepath);
      if (exists) return filepath;
      
      await RNFS.downloadFile({ fromUrl: url, toFile: filepath });
      await this.cleanupOldCache();
      
      return filepath;
    } catch (error) {
      return url; // Fallback to original URL
    }
  }
};
```

### 📊 Analytics et Monitoring

#### Performance Monitoring
```javascript
// Real-time performance tracking
class PerformanceMonitor {
  constructor() {
    this.metrics = {
      screenLoadTimes: new Map(),
      apiResponseTimes: new Map(),
      memoryUsage: [],
      crashReports: []
    };
  }
  
  // Screen performance tracking
  trackScreenLoad(screenName, startTime) {
    const loadTime = Date.now() - startTime;
    
    this.metrics.screenLoadTimes.set(screenName, {
      loadTime,
      timestamp: Date.now()
    });
    
    // Send to analytics if > 2s
    if (loadTime > 2000) {
      this.reportSlowLoad(screenName, loadTime);
    }
  }
  
  // API performance monitoring
  trackAPICall(endpoint, duration, success) {
    const existing = this.metrics.apiResponseTimes.get(endpoint) || [];
    existing.push({ duration, success, timestamp: Date.now() });
    
    // Keep only last 100 calls per endpoint
    if (existing.length > 100) {
      existing.shift();
    }
    
    this.metrics.apiResponseTimes.set(endpoint, existing);
  }
  
  // Memory monitoring
  monitorMemory() {
    setInterval(() => {
      if (Platform.OS === 'ios') {
        // iOS memory monitoring
        NativeModules.MemoryMonitor?.getCurrentMemoryUsage()
          .then(usage => {
            this.metrics.memoryUsage.push({
              usage,
              timestamp: Date.now()
            });
            
            // Alert if memory > 200MB
            if (usage > 200 * 1024 * 1024) {
              this.handleHighMemoryUsage();
            }
          });
      }
    }, 30000); // Check every 30s
  }
}
```

---

## 7️⃣ Sécurité Mobile

### 🔐 Security Best Practices

#### Application Security
```javascript
// Certificate pinning
const CertificatePinning = {
  setupPinning: () => {
    // Pin API certificate
    NetworkingIOS.addSSLPinningForDomain('api.bwatoo.fr', {
      certificateHash: 'SHA256:ABCD1234...' // Production cert hash
    });
  },
  
  // Runtime application self-protection
  detectJailbreak: async () => {
    if (Platform.OS === 'ios') {
      return JailMonkey.isJailBroken();
    } else {
      return JailMonkey.isRooted();
    }
  },
  
  // Debug detection
  detectDebugger: () => {
    return __DEV__ || JailMonkey.isDebuggedMode();
  }
};

// API request signing
class SecureAPIClient {
  constructor() {
    this.privateKey = this.generateDeviceKey();
  }
  
  async signRequest(requestData) {
    const timestamp = Date.now();
    const nonce = this.generateNonce();
    
    const payload = {
      ...requestData,
      timestamp,
      nonce
    };
    
    const signature = await this.createSignature(payload);
    
    return {
      ...payload,
      signature
    };
  }
  
  async createSignature(payload) {
    const message = JSON.stringify(payload);
    return CryptoJS.HmacSHA256(message, this.privateKey).toString();
  }
}
```

#### Data Protection
```javascript
// Biometric authentication with secure enclave
const BiometricSecurity = {
  // Store sensitive data in secure enclave (iOS) / Keystore (Android)
  storeSecurely: async (key, data) => {
    const options = {
      accessControl: 'BiometryAny',
      authenticatePrompt: 'Authentifiez-vous pour accéder',
      service: 'BwatooSecureStorage',
      touchID: true,
      showModal: true
    };
    
    return await Keychain.setInternetCredentials(key, 'user', data, options);
  },
  
  // Retrieve with biometric verification
  retrieveSecurely: async (key) => {
    const options = {
      authenticatePrompt: 'Authentifiez-vous pour accéder'
    };
    
    const credentials = await Keychain.getInternetCredentials(key, options);
    return credentials ? credentials.password : null;
  },
  
  // Screen recording prevention
  preventScreenRecording: () => {
    if (Platform.OS === 'ios') {
      // iOS: Blur app when backgrounded
      AppState.addEventListener('change', (nextAppState) => {
        if (nextAppState === 'background') {
          // Add blur overlay
          this.addSecurityOverlay();
        } else if (nextAppState === 'active') {
          this.removeSecurityOverlay();
        }
      });
    } else {
      // Android: FLAG_SECURE
      const activity = ReactNativeAndroid.getCurrentActivity();
      activity.getWindow().setFlags(
        WindowManager.LayoutParams.FLAG_SECURE,
        WindowManager.LayoutParams.FLAG_SECURE
      );
    }
  }
};
```

---

## 8️⃣ Plan de Développement

### 📅 Roadmap de Développement

#### **Phase 1 : Foundation (Mois 1-2)**
```
Sprint 1 (2 semaines):
- Setup projet React Native + TypeScript
- Architecture navigation (React Navigation)
- Design system de base (NativeBase)
- Authentication flow (phone OTP)
- API client avec offline support

Sprint 2 (2 semaines):
- Home screen avec feed posts
- Search & filters basiques
- Post detail view
- Basic profile management
- Push notifications setup

Sprint 3 (2 semaines):
- Post creation flow complet
- Camera integration optimisée
- Géolocalisation précise
- Image upload & compression
- Form validation robuste

Sprint 4 (2 semaines):
- Chat messaging système
- Real-time avec WebSocket
- Voice messages
- Photo sharing in chat
- Notification management
```

#### **Phase 2 : Premium Features (Mois 3-4)**
```
Sprint 5 (2 semaines):
- Premium Ads UI/UX
- Package selection optimisée
- Mobile Money base (Orange/MTN)
- Purchase flow simplifié
- Payment confirmation

Sprint 6 (2 semaines):
- Advanced search features
- Voice search integration
- Visual search (photo)
- Saved searches & alerts
- Filter presets

Sprint 7 (2 semaines):
- User profile avancé
- Verification system
- Seller ratings & reviews
- Trust & safety features
- Social proof elements

Sprint 8 (2 semaines):
- Performance optimizations
- Offline sync robuste
- Image caching strategy
- Memory optimization
- Testing & debugging
```

#### **Phase 3 : Market Readiness (Mois 5-6)**
```
Sprint 9 (2 semaines):
- Multi-country support
- Localization complete
- Currency handling
- Country-specific features
- Legal compliance

Sprint 10 (2 semaines):
- Advanced analytics
- User behavior tracking
- Performance monitoring
- Crash reporting
- A/B testing framework

Sprint 11 (2 semaines):
- Security hardening
- Penetration testing fixes
- Certificate pinning
- Anti-tampering measures
- Privacy compliance

Sprint 12 (2 semaines):
- App store optimization
- Beta testing program
- Performance final tuning
- Documentation complete
- Launch preparation
```

### 👥 Équipe Recommandée

#### Core Team
- **1 Lead Mobile Developer** : Architecture & coordination
- **2 React Native Developers** : Feature development
- **1 Mobile UI/UX Designer** : Interface spécifique mobile
- **1 QA Mobile Specialist** : Testing iOS/Android
- **0.5 DevOps** : CI/CD & app store deployment

#### Support Team (Partagé)
- **Backend Developer** : API adaptations mobiles
- **Product Manager** : Roadmap & requirements
- **Security Specialist** : Mobile security review

### 💰 Budget Estimé

#### Développement (6 mois)
- **Lead Mobile Dev** : 6 mois × 8,000€ = 48,000€
- **React Native Devs** : 2 × 6 mois × 6,000€ = 72,000€
- **Mobile Designer** : 6 mois × 4,000€ = 24,000€
- **QA Mobile** : 6 mois × 3,500€ = 21,000€
- **DevOps Mobile** : 3 mois × 4,000€ = 12,000€

#### Infrastructure & Tools
- **Development tools** : 3,000€
- **Testing devices** : 5,000€
- **App store fees** : 200€/an
- **CI/CD services** : 100€/mois × 6 = 600€
- **Analytics/Monitoring** : 200€/mois × 6 = 1,200€

#### Marketing & Launch
- **App store optimization** : 5,000€
- **Beta testing program** : 2,000€
- **Launch marketing** : 10,000€

**TOTAL Phase Mobile : 205,000€**

---

## 9️⃣ Success Metrics & KPIs

### 📊 Métriques de Performance

#### Adoption & Engagement
```
Adoption Targets (6 mois post-launch):
- Downloads: 100,000+ (Afrique francophone)
- DAU (Daily Active Users): 15,000+
- MAU (Monthly Active Users): 50,000+
- Retention D1: 70%+
- Retention D7: 40%+
- Retention D30: 25%+

Engagement Targets:
- Session duration: 8+ minutes
- Sessions/user/day: 3+
- Posts created/month: 5,000+
- Messages sent/day: 10,000+
- Premium purchases/month: 500+
```

#### Business Metrics
```
Revenue Targets:
- Mobile Money transaction success: 95%+
- Premium Ads via mobile: 60% of total
- Average revenue per user (ARPU): 5€/month
- Conversion Free → Paid: 15%+
- Customer lifetime value (CLV): 25€+

Operational Metrics:
- App crash rate: <0.1%
- API response time: <500ms
- Offline functionality: 90% features
- Customer support tickets: <5% users
- App store rating: 4.3+ stars
```

### 🎯 Success Criteria

#### Technical Excellence
- **Performance** : App loads <3s on 3G
- **Reliability** : 99.9% uptime mobile services
- **Security** : Zero major security incidents
- **Compatibility** : Android 8+ et iOS 13+ support
- **Accessibility** : WCAG 2.1 AA compliance

#### Market Position
- **Top 3** classified apps in target countries
- **#1** for African multi-country classifieds
- **50%** market share premium features
- **Recognition** : App store featuring
- **Community** : 10,000+ active community members

---

## 🔚 Conclusion

### 🎯 Vision Mobile

Cette application mobile représente bien plus qu'une simple extension de Bwatoo. C'est l'outil qui va **démocratiser l'accès au commerce digital** pour des millions d'Africains, en supprimant les barrières techniques et économiques.

### 🚀 Avantages Concurrentiels

1. **Offline-First** : Fonctionne sans connexion stable
2. **Mobile Money Natif** : Paiements locaux seamless
3. **Multi-Pays Unifié** : Une app pour toute l'Afrique
4. **AI-Powered** : Recommandations intelligentes
5. **Community-Driven** : Trust & safety prioritaires

### 📈 Impact Attendu

Avec cette app, Bwatoo va pouvoir :
- **Multiplier par 5** sa base utilisateurs
- **Quadrupler** ses revenus Premium Ads
- **Être présent** sur tous les smartphones africains
- **Devenir la référence** du commerce mobile en Afrique

### 🛣️ Next Steps

1. **Validation stakeholders** : Approval budget & timeline
2. **Team assembly** : Recrutement équipe mobile
3. **Technical foundation** : Setup développement
4. **Design sprint** : UX/UI optimization mobile-first
5. **Development start** : Sprint 1 foundation

---

**"L'Afrique mobile d'abord - Bwatoo dans chaque poche"** 📱🌍

*Document technique évolutif - Version 1.0 - Juillet 2025*