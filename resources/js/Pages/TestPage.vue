<script setup>
import { Link } from '@inertiajs/vue3';
import heroImage from '../../images/testpage-hero.webp';

const challengeCards = [
    {
        title: 'Limited Visibility',
        copy: 'Buyers cannot easily verify where coffee comes from or confirm sustainable practices on the ground.',
    },
    {
        title: 'Fragmented Records',
        copy: "Paper-based systems create disconnected information that's difficult to access or validate across the supply chain.",
    },
    {
        title: 'Weak Trust',
        copy: 'Without transparent data, market actors struggle to build confidence in sourcing claims and quality promises.',
    },
    {
        title: 'Market Barriers',
        copy: 'Small producers and cooperatives lack the tools to prove value and access premium buyers willing to pay more.',
    },
];

const infrastructureFeatures = [
    {
        title: 'Verified Origins',
        copy: 'Every coffee batch includes verifiable information about farm location, producer details, and harvest practices.',
    },
    {
        title: 'Connected Network',
        copy: 'Farmers, cooperatives, exporters, and buyers access the same trusted data in one unified platform.',
    },
    {
        title: 'Market Access',
        copy: 'Producers gain visibility with premium buyers actively seeking responsibly sourced coffee.',
    },
];

const processFlow = [
    { title: 'Register', copy: 'Farmers add origin details' },
    { title: 'Record', copy: 'Harvest and processing logged' },
    { title: 'Batch', copy: 'Organize lots with quality data' },
    { title: 'Source', copy: 'Buyers discover verified coffee' },
];

const processDetails = [
    {
        title: 'Register & Record',
        copy: 'Farmers and cooperatives create profiles and document coffee origin, farming practices, and initial quality assessments.',
    },
    {
        title: 'Document Processing',
        copy: 'Harvest dates, milling methods, fermentation processes, and quality scores are added to each batch record.',
    },
    {
        title: 'Batch Organization',
        copy: 'Coffee is grouped into traceable batches with complete production history, certifications, and lab results visible.',
    },
    {
        title: 'Discover & Trade',
        copy: 'Buyers search for specific origins, qualities, and ethical attributes, sourcing directly with verified information.',
    },
];

const platformFeatures = [
    {
        title: 'Coffee Batch Traceability',
        copy: 'Every batch includes GPS coordinates, farm details, and complete journey from harvest to export with timestamped records.',
        icon: 'geo-alt',
    },
    {
        title: 'Origin & Producer Visibility',
        copy: 'Farmers and cooperatives create profiles showcasing their practices, certifications, and unique value propositions to buyers.',
        icon: 'eyeglasses',
    },
    {
        title: 'Quality & Sourcing Records',
        copy: 'Cupping scores, lab analyses, moisture content, and grading data are stored digitally for easy reference and comparison.',
        icon: 'clipboard2-check',
    },
    {
        title: 'Exchange Marketplace',
        copy: 'Buyers and sellers connect directly through a trusted platform with transparent pricing, availability, and negotiation tools.',
        icon: 'handshake',
    },
    {
        title: 'Ethical Sourcing Support',
        copy: 'Certifications, sustainability claims, and social impact metrics are verified and displayed to support responsible buying decisions.',
        icon: 'leaf',
    },
    {
        title: 'Transparent Supply Chain',
        copy: 'Real-time updates on coffee movement, storage conditions, and ownership transfers build confidence at every stage.',
        icon: 'cup-straw',
    },
];

const whyItems = [
    {
        title: 'Builds Buyer Confidence',
        copy: 'Verified data eliminates guesswork and supports informed purchasing decisions with reduced risk.',
    },
    {
        title: 'Encourages Fairer Participation',
        copy: 'Transparent pricing and access levels the playing field for small producers competing in global markets.',
    },
    {
        title: 'Rewards Transparency',
        copy: 'Producers who invest in quality and sustainability practices receive recognition and premium pricing opportunities.',
    },
    {
        title: 'Strengthens Communities',
        copy: 'Direct relationships between buyers and farmers support long-term partnerships and community investment.',
    },
];

const impactStats = [
    {
        value: '3X',
        title: 'Producer Visibility',
        copy: 'Farmers reach three times more potential buyers through platform discovery tools and marketplace exposure.',
    },
    {
        value: '25%',
        title: 'Faster Transactions',
        copy: 'Digitized records and transparent pricing reduce negotiation time and accelerate coffee sales cycles.',
    },
    {
        value: '85%',
        title: 'Data Accuracy',
        copy: 'Digital documentation eliminates manual errors and provides reliable information for quality assurance.',
    },
];

const impactOutcomes = [
    {
        title: 'More Visibility for Farmers',
        copy: 'Smallholders and cooperatives gain profiles that showcase their unique coffee and sustainable practices to global buyers.',
    },
    {
        title: 'Stronger Value Chain Trust',
        copy: 'Shared access to verifiable data builds confidence between all market actors and reduces disputes over quality or origin.',
    },
    {
        title: 'Better Sourcing Decisions',
        copy: 'Buyers identify responsibly sourced coffee faster and with greater confidence in ethical claims and quality consistency.',
    },
];

const roadmap = [
    {
        label: 'Today',
        copy: 'Platform connects farmers, cooperatives, exporters, and buyers with verified coffee data and transparent pricing.',
    },
    {
        label: 'Next Phase',
        copy: 'Expand coverage across African coffee regions, integrate payment systems, and support traceability from farm to roaster.',
    },
    {
        label: 'Long-term Vision',
        copy: 'Establish Bean Origin as the global standard for ethical coffee sourcing with immutable records and AI-powered matching.',
    },
];

const userGroups = [
    {
        title: 'Farmers & Cooperatives',
        copy: 'Smallholders and farming groups gain visibility, prove quality, and access premium buyers willing to pay more for responsibly sourced coffee.',
    },
    {
        title: 'Exporters',
        copy: 'Export companies streamline documentation, verify batch origins, and match supply with buyer demand efficiently through the marketplace.',
    },
    {
        title: 'Roasters & Buyers',
        copy: 'Roasters and retailers identify verified coffee sources, confirm ethical claims, and build direct relationships with producers they trust.',
    },
    {
        title: 'Partners & Investors',
        copy: 'Sustainability partners, development agencies, and investors support transparent trade that delivers measurable social and environmental impact.',
    },
];

const ctaCards = [
    {
        title: 'Source Ethical Coffee',
        copy: 'Find verified, traceable coffee from farms and cooperatives committed to quality and sustainability.',
    },
    {
        title: 'Gain Market Access',
        copy: 'Showcase your coffee to buyers actively seeking responsibly sourced beans from your region.',
    },
    {
        title: 'Partner With Us',
        copy: 'Collaborate on development initiatives, technology integration, or regional expansion programs.',
    },
];
</script>

<template>
    <main class="coffee-deck">
        <section class="slide slide-cover">
            <div class="cover-copy">
                <h1>Ethical Coffee Sourcing, Powered by Transparency</h1>
                <p class="cover-lead">
                    Bean Origin connects farmers, cooperatives, exporters, and buyers through a trusted digital exchange
                    built for traceability, accountability, and better market access.
                </p>

                <div class="button-row">
                    <Link href="/register" class="btn-main">Source Coffee</Link>
                    <a href="#challenge" class="btn-outline">Request Demo</a>
                    <a href="#cta" class="btn-outline">Partner With Us</a>
                </div>
            </div>

            <div class="cover-media">
                <img :src="heroImage" alt="Coffee producer holding a tablet" class="cover-image" />
            </div>
        </section>

        <section id="challenge" class="slide slide-green slide-challenge">
            <h2>The Coffee Sourcing Challenge</h2>
            <p class="section-lead challenge-lead">
                The coffee industry faces critical transparency gaps that limit trust and market access. Without verifiable
                records, buyers struggle to confirm ethical claims while producers lack visibility into premium markets.
            </p>

            <div class="challenge-grid">
                <article v-for="item in challengeCards" :key="item.title" class="challenge-card">
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.copy }}</p>
                </article>
            </div>
        </section>

        <section class="slide slide-green slide-infrastructure">
            <h2>Building the Digital Infrastructure for Ethical Trade</h2>
            <p class="section-lead">
                Bean Origin is a coffee exchange built specifically for ethical sourcing. We digitize coffee batch records and
                link them to a transparent system, making supply chains more accountable and efficient.
            </p>

            <div class="infrastructure-grid">
                <div class="infra-illustration-panel">
                    <div class="desk-illustration">
                        <div class="desk-screen">
                            <div class="desk-note note-a"></div>
                            <div class="desk-note note-b"></div>
                            <div class="desk-line line-a"></div>
                            <div class="desk-line line-b"></div>
                            <div class="desk-line line-c"></div>
                        </div>
                        <div class="desk-base"></div>
                        <div class="desk-cup"></div>
                        <div class="desk-plant">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>

                <div class="infra-feature-list">
                    <article v-for="item in infrastructureFeatures" :key="item.title" class="infra-feature">
                        <h3>{{ item.title }}</h3>
                        <p>{{ item.copy }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="slide slide-white slide-how">
            <h2>How Bean Origin Works</h2>
            <p class="section-lead">
                From farm to buyer, every step is documented and accessible. This simple flow transforms coffee sourcing into a
                transparent, trustworthy process.
            </p>

            <div class="process-flow">
                <svg class="process-wave" viewBox="0 0 1560 360" aria-hidden="true">
                    <defs>
                        <marker id="process-arrow" markerWidth="14" markerHeight="14" refX="11" refY="7" orient="auto">
                            <path d="M0,0 L14,7 L0,14" fill="none" stroke="#5e667b" stroke-width="1.8" />
                        </marker>
                    </defs>
                    <circle cx="42" cy="210" r="16" fill="#495062" />
                    <path d="M58 210 H168" fill="none" stroke="#5e667b" stroke-width="6" stroke-linecap="round" />
                    <path
                        d="M168 210 C168 38 486 38 486 210"
                        fill="none"
                        stroke="#5e667b"
                        stroke-width="6"
                        stroke-linecap="round"
                        marker-end="url(#process-arrow)"
                    />
                    <path
                        d="M486 210 C486 350 804 350 804 210"
                        fill="none"
                        stroke="#61697f"
                        stroke-width="6"
                        stroke-linecap="round"
                        marker-end="url(#process-arrow)"
                    />
                    <path
                        d="M804 210 C804 54 1122 54 1122 210"
                        fill="none"
                        stroke="#697187"
                        stroke-width="6"
                        stroke-linecap="round"
                        marker-end="url(#process-arrow)"
                    />
                    <path
                        d="M1122 210 C1122 346 1440 346 1440 210"
                        fill="none"
                        stroke="#727b92"
                        stroke-width="6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M1440 210 H1532"
                        fill="none"
                        stroke="#727b92"
                        stroke-width="6"
                        stroke-linecap="round"
                        marker-end="url(#process-arrow)"
                    />
                </svg>

                <div class="process-steps">
                    <article v-for="(item, index) in processFlow" :key="item.title" :class="['process-step', `step-${index}`]">
                        <h3>{{ item.title }}</h3>
                        <p>{{ item.copy }}</p>
                    </article>
                </div>
            </div>

            <div class="detail-grid">
                <article v-for="item in processDetails" :key="item.title" class="detail-item">
                    <div class="detail-icon"><i class="bi bi-bag-check"></i></div>
                    <div>
                        <h3>{{ item.title }}</h3>
                        <p>{{ item.copy }}</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="slide slide-white slide-features">
            <h2>Key Platform Features</h2>

            <div class="feature-grid">
                <article v-for="item in platformFeatures" :key="item.title" class="feature-item">
                    <div class="feature-icon"><i :class="`bi bi-${item.icon}`"></i></div>
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.copy }}</p>
                </article>
            </div>
        </section>

        <section class="slide slide-white slide-why">
            <div class="why-art">
                <svg viewBox="0 0 520 980" class="branch-svg" aria-hidden="true">
                    <g fill="none" stroke="#676f86" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M164 950 C176 830 192 720 240 598 C294 460 312 330 298 204" />
                        <path d="M0 248 C70 232 120 236 166 276 C208 312 234 352 250 420" />
                        <path d="M214 518 C272 470 334 446 410 462 C458 474 490 500 520 548" />
                        <path d="M248 760 C314 720 376 706 468 730" />
                        <path d="M118 920 C92 896 72 866 58 832" />
                        <path d="M114 286 C132 230 170 186 224 156" />
                        <path d="M286 246 C334 216 376 206 430 214" />
                        <path d="M320 572 C352 530 392 512 446 512" />
                        <path d="M372 840 C400 812 432 794 470 788" />
                        <path d="M32 240 C18 206 6 188 -18 170" />
                        <ellipse cx="92" cy="238" rx="22" ry="42" />
                        <ellipse cx="184" cy="194" rx="24" ry="52" />
                        <ellipse cx="258" cy="148" rx="26" ry="54" />
                        <ellipse cx="358" cy="220" rx="26" ry="50" />
                        <ellipse cx="438" cy="234" rx="24" ry="44" />
                        <ellipse cx="362" cy="500" rx="24" ry="44" />
                        <ellipse cx="460" cy="500" rx="22" ry="40" />
                        <ellipse cx="384" cy="806" rx="22" ry="40" />
                        <ellipse cx="40" cy="826" rx="22" ry="42" />
                        <ellipse cx="316" cy="564" rx="18" ry="28" />
                        <ellipse cx="292" cy="584" rx="18" ry="30" />
                        <ellipse cx="334" cy="592" rx="18" ry="30" />
                    </g>
                </svg>
            </div>

            <div class="why-copy">
                <h2>Why Ethical Sourcing Matters</h2>
                <p class="section-lead">
                    Ethical sourcing transforms the coffee economy from transactional to relational. When producers are
                    recognized for quality and sustainability, everyone benefits.
                </p>

                <div class="why-list">
                    <article v-for="item in whyItems" :key="item.title" class="why-item">
                        <div class="why-marker"></div>
                        <div>
                            <h3>{{ item.title }}</h3>
                            <p>{{ item.copy }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="slide slide-white slide-impact">
            <h2>Measurable Impact</h2>
            <p class="section-lead">
                Bean Origin creates tangible value across the coffee value chain, from increased visibility for producers to
                better decision-making for buyers.
            </p>

            <div class="impact-stats">
                <article v-for="item in impactStats" :key="item.title" class="impact-stat">
                    <div class="impact-value">{{ item.value }}</div>
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.copy }}</p>
                </article>
            </div>

            <div class="impact-line">
                <div class="impact-dot"></div>
                <div class="impact-dot"></div>
                <div class="impact-dot"></div>
            </div>

            <div class="impact-notes">
                <article v-for="item in impactOutcomes" :key="item.title" class="impact-note">
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.copy }}</p>
                </article>
            </div>
        </section>

        <section class="slide slide-white slide-future">
            <h2>The Future of Coffee Trade</h2>

            <div class="future-quote">
                "Bean Origin is building the future of ethical coffee trade by creating digital infrastructure that supports
                transparency, trust, and inclusive participation across coffee supply chains."
            </div>

            <div class="future-grid">
                <div class="pyramid">
                    <div class="pyramid-level level-1"><span>1</span></div>
                    <div class="pyramid-level level-2"><span>2</span></div>
                    <div class="pyramid-level level-3"><span>3</span></div>
                </div>

                <div class="roadmap-list">
                    <article v-for="item in roadmap" :key="item.label" class="roadmap-item">
                        <h3>{{ item.label }}</h3>
                        <p>{{ item.copy }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="slide slide-white slide-users">
            <h2>Who Uses Bean Origin</h2>
            <p class="section-lead">
                From origin to cup, every stakeholder benefits from transparent, accountable coffee exchange. Our platform
                serves the entire value chain.
            </p>

            <div class="user-grid">
                <article v-for="item in userGroups" :key="item.title" class="user-card">
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.copy }}</p>
                </article>
            </div>

            <div class="user-arc">
                <svg viewBox="0 0 1100 460" class="user-arc-svg" aria-hidden="true">
                    <path
                        d="M140 402 A 410 410 0 0 1 345 118"
                        fill="none"
                        stroke="#e1e3e8"
                        stroke-width="138"
                        stroke-linecap="round"
                    />
                    <path
                        d="M390 108 A 320 320 0 0 1 520 42"
                        fill="none"
                        stroke="#e1e3e8"
                        stroke-width="132"
                        stroke-linecap="round"
                    />
                    <path
                        d="M580 42 A 320 320 0 0 1 710 108"
                        fill="none"
                        stroke="#e1e3e8"
                        stroke-width="132"
                        stroke-linecap="round"
                    />
                    <path
                        d="M755 118 A 410 410 0 0 1 960 402"
                        fill="none"
                        stroke="#e1e3e8"
                        stroke-width="138"
                        stroke-linecap="round"
                    />
                </svg>

                <div class="user-arc-icon arc-icon-a"><i class="bi bi-person"></i></div>
                <div class="user-arc-icon arc-icon-b"><i class="bi bi-box-arrow-up"></i></div>
                <div class="user-arc-icon arc-icon-c"><i class="bi bi-cup-hot"></i></div>
                <div class="user-arc-icon arc-icon-d"><i class="bi bi-file-earmark-check"></i></div>
            </div>
        </section>

        <section id="cta" class="slide slide-white slide-cta">
            <h2>Join the Coffee Exchange Built for Tomorrow</h2>
            <p class="section-lead">
                Whether you're sourcing coffee, growing beans, or supporting ethical trade, Bean Origin provides the digital
                infrastructure to connect, verify, and trade with confidence.
            </p>

            <div class="cta-grid">
                <article v-for="item in ctaCards" :key="item.title" class="cta-card">
                    <h3>{{ item.title }}</h3>
                    <p>{{ item.copy }}</p>
                </article>
            </div>

            <div class="button-row">
                <a href="#challenge" class="btn-main">Request a Demo</a>
                <Link href="/login" class="btn-outline">Contact Sales</Link>
            </div>
        </section>
    </main>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

.coffee-deck {
    background: #f6f5ef;
    color: #50586d;
    font-family: 'Instrument Sans', sans-serif;
}

.slide {
    width: min(100%, 2048px);
    min-height: 100vh;
    padding: 72px 76px;
    background: #fafaf7;
    border-bottom: 1px solid rgba(87, 95, 114, 0.06);
    overflow: hidden;
}

.slide h1,
.slide h2,
.slide h3 {
    margin: 0;
    font-family: 'Space Grotesk', sans-serif;
    letter-spacing: -0.05em;
    color: #40485b;
}

.slide h2 {
    font-size: clamp(3rem, 5vw, 5rem);
    line-height: 1.05;
}

.section-lead {
    max-width: 90%;
    margin: 34px 0 0;
    font-size: 1.18rem;
    line-height: 1.75;
    color: #5d657b;
}

.button-row {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.btn-main,
.btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 58px;
    padding: 0 28px;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;
}

.btn-main {
    background: #40485b;
    color: #ffffff;
}

.btn-outline {
    background: transparent;
    color: #40485b;
    border: 2px solid rgba(64, 72, 91, 0.42);
}

.slide-cover {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(420px, 1fr);
    align-items: stretch;
    padding: 0;
    background: #fafaf7;
}

.cover-copy {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 90px 76px;
}

.cover-copy h1 {
    max-width: 10.8ch;
    font-size: clamp(4rem, 6vw, 5.7rem);
    line-height: 1.03;
}

.cover-lead {
    max-width: 37ch;
    margin: 40px 0 0;
    font-size: 1.22rem;
    line-height: 1.72;
}

.cover-copy .button-row {
    margin-top: 36px;
}

.cover-media {
    min-height: 100vh;
}

.cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.slide-green {
    background:
        radial-gradient(circle at 22% 25%, rgba(202, 255, 202, 0.45), transparent 24%),
        radial-gradient(circle at 72% 64%, rgba(183, 247, 181, 0.42), transparent 24%),
        #a5ef9b;
}

.slide-challenge h2,
.slide-infrastructure h2 {
    max-width: 14ch;
}

.challenge-lead {
    max-width: 96%;
    color: #0f151e;
}

.challenge-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px 28px;
    margin-top: 38px;
}

.challenge-card {
    min-height: 248px;
    padding: 30px 34px 28px;
    border-left: 12px solid #3a3f51;
    border-radius: 12px;
    background: rgba(165, 239, 155, 0.12);
    box-shadow: inset 0 0 0 2px rgba(218, 190, 244, 0.9);
}

.challenge-card h3 {
    font-size: 2rem;
    line-height: 1.04;
    color: #11171f;
}

.challenge-card p,
.infra-feature p,
.detail-item p,
.feature-item p,
.why-item p,
.impact-stat p,
.impact-note p,
.roadmap-item p,
.user-card p,
.cta-card p {
    margin: 22px 0 0;
    font-size: 1.12rem;
    line-height: 1.7;
    color: #11171f;
}

.infrastructure-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(380px, 0.95fr);
    gap: 52px;
    align-items: start;
    margin-top: 40px;
}

.infra-illustration-panel {
    min-height: 720px;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.24);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 42px;
}

.desk-illustration {
    position: relative;
    width: min(100%, 560px);
    padding-bottom: 40px;
}

.desk-screen {
    position: relative;
    width: 68%;
    height: 250px;
    margin: 0 auto;
    background: transparent;
    border: 5px solid #0d1115;
    border-radius: 14px 14px 6px 6px;
}

.desk-screen::before {
    content: '';
    position: absolute;
    left: 18px;
    bottom: -2px;
    width: calc(100% + 72px);
    height: 4px;
    background: #0d1115;
    transform: translateX(-36px);
}

.desk-note {
    position: absolute;
    width: 64px;
    height: 88px;
    border: 4px solid #0d1115;
    border-radius: 6px;
}

.desk-note::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 18px;
    width: 26px;
    height: 4px;
    background: #0d1115;
    box-shadow: 0 18px 0 #0d1115, 0 36px 0 #0d1115;
}

.note-a {
    left: 94px;
    top: 48px;
}

.note-b {
    left: 138px;
    top: 112px;
}

.desk-line {
    position: absolute;
    height: 4px;
    border-radius: 999px;
    background: #0d1115;
}

.line-a {
    top: 92px;
    left: 228px;
    width: 72px;
}

.line-b {
    top: 132px;
    left: 240px;
    width: 156px;
}

.line-c {
    top: 168px;
    left: 254px;
    width: 118px;
}

.desk-base {
    width: 76%;
    height: 60px;
    margin: 18px auto 0;
    border: 5px solid #0d1115;
    border-top: 0;
    clip-path: polygon(7% 0, 93% 0, 100% 100%, 0 100%);
}

.desk-cup {
    position: absolute;
    left: 12px;
    bottom: 86px;
    width: 56px;
    height: 68px;
    border: 4px solid #0d1115;
    border-radius: 0 0 16px 16px;
}

.desk-cup::before {
    content: '';
    position: absolute;
    right: -20px;
    top: 16px;
    width: 20px;
    height: 20px;
    border: 4px solid #0d1115;
    border-left: 0;
    border-radius: 0 12px 12px 0;
}

.desk-cup::after {
    content: '';
    position: absolute;
    left: 18px;
    top: -18px;
    width: 18px;
    height: 24px;
    border-top: 4px solid #0d1115;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-radius: 50%;
}

.desk-plant {
    position: absolute;
    right: 18px;
    bottom: 72px;
    width: 78px;
    height: 84px;
    border: 4px solid #0d1115;
    border-radius: 6px 6px 18px 18px;
}

.desk-plant span {
    position: absolute;
    bottom: 62px;
    width: 18px;
    border: 4px solid #0d1115;
    border-radius: 18px;
}

.desk-plant span:nth-child(1) {
    left: 12px;
    height: 48px;
}

.desk-plant span:nth-child(2) {
    left: 30px;
    height: 72px;
}

.desk-plant span:nth-child(3) {
    left: 48px;
    height: 58px;
}

.infra-feature-list {
    display: grid;
    gap: 56px;
    padding-top: 18px;
}

.infra-feature {
    padding-left: 30px;
    border-left: 8px solid #394051;
}

.infra-feature h3 {
    font-size: 2rem;
    color: #11171f;
}

.slide-how h2,
.slide-features h2,
.slide-why h2,
.slide-impact h2,
.slide-future h2,
.slide-users h2,
.slide-cta h2 {
    max-width: 14ch;
}

.slide-white {
    background: #fafaf7;
}

.process-flow {
    position: relative;
    margin-top: 56px;
    min-height: 360px;
    overflow-x: auto;
}

.process-wave {
    width: 1560px;
    height: 300px;
    display: block;
}

.process-steps {
    position: relative;
    width: 1560px;
    height: 0;
}

.process-step {
    position: absolute;
    width: 238px;
    text-align: center;
}

.step-0 {
    left: 190px;
    top: -208px;
}

.step-1 {
    left: 500px;
    top: -192px;
}

.step-2 {
    left: 820px;
    top: -208px;
}

.step-3 {
    left: 1134px;
    top: -192px;
}

.process-step h3 {
    font-size: 2rem;
    line-height: 1.04;
}

.process-step p {
    margin: 14px 0 0;
    font-size: 1rem;
    line-height: 1.34;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 42px 64px;
    margin-top: 56px;
}

.detail-item {
    display: grid;
    grid-template-columns: 82px 1fr;
    gap: 22px;
    align-items: start;
}

.detail-icon {
    width: 64px;
    height: 64px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: #e6e7ea;
    color: #c5c7cf;
    font-size: 1.6rem;
}

.detail-item h3 {
    font-size: 1.9rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 50px 100px;
    margin-top: 54px;
}

.feature-item {
    display: grid;
    gap: 16px;
    align-content: start;
}

.feature-icon {
    color: #8df07f;
    font-size: 3rem;
    line-height: 1;
}

.feature-item h3 {
    font-size: 1.9rem;
}

.slide-why {
    display: grid;
    grid-template-columns: minmax(320px, 0.88fr) minmax(0, 1.12fr);
    gap: 64px;
    align-items: stretch;
}

.why-art {
    min-height: 980px;
    display: flex;
    align-items: stretch;
    padding-right: 12px;
}

.branch-svg {
    width: 100%;
    height: 100%;
    opacity: 0.92;
}

.why-copy {
    padding-top: 10px;
}

.why-list {
    display: grid;
    gap: 42px;
    margin-top: 36px;
}

.why-item {
    display: grid;
    grid-template-columns: 70px 1fr;
    gap: 22px;
}

.why-marker {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    background: #e5e7ec;
    box-shadow: inset 0 0 0 2px rgba(92, 100, 118, 0.08);
}

.why-item h3,
.impact-stat h3,
.impact-note h3,
.roadmap-item h3,
.user-card h3,
.cta-card h3 {
    font-size: 1.9rem;
    line-height: 1.08;
}

.impact-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 34px;
    margin-top: 62px;
    text-align: center;
}

.impact-value {
    font-family: 'Space Grotesk', sans-serif;
    font-size: clamp(4rem, 7vw, 5.8rem);
    line-height: 1;
    color: #5c637a;
}

.impact-stat h3 {
    margin-top: 22px;
}

.impact-stat p {
    margin-top: 18px;
}

.impact-line {
    position: relative;
    height: 88px;
    margin-top: 30px;
    border-top: 4px solid #d0d2d8;
}

.impact-dot {
    position: absolute;
    top: -14px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #3f4659;
}

.impact-dot::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 100%;
    width: 4px;
    height: 58px;
    background: #d0d2d8;
    transform: translateX(-50%);
}

.impact-dot:nth-child(1) {
    left: 15.5%;
}

.impact-dot:nth-child(2) {
    left: 49%;
}

.impact-dot:nth-child(3) {
    right: 15.5%;
}

.impact-notes {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 34px;
    margin-top: 10px;
}

.impact-note {
    text-align: center;
}

.future-quote {
    max-width: 96%;
    margin-top: 54px;
    padding-left: 38px;
    border-left: 4px solid #4c5468;
    font-size: 1.12rem;
    line-height: 1.9;
    color: #5d657b;
}

.future-grid {
    display: grid;
    grid-template-columns: minmax(360px, 0.95fr) minmax(0, 1.05fr);
    gap: 42px;
    align-items: end;
    margin-top: 38px;
}

.pyramid {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    gap: 8px;
    padding-top: 28px;
}

.pyramid-level {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #596178;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 3rem;
    background: #dde0e6;
    clip-path: polygon(50% 0, 100% 100%, 0 100%);
}

.level-1 {
    width: 310px;
    height: 230px;
}

.level-2 {
    width: 460px;
    height: 290px;
}

.level-3 {
    width: 620px;
    height: 330px;
}

.roadmap-list {
    display: grid;
}

.roadmap-item {
    padding: 36px 0;
    border-top: 2px solid #d5d7de;
}

.roadmap-item:first-child {
    border-top: 0;
}

.roadmap-item p {
    max-width: 96%;
}

.slide-users {
    position: relative;
}

.user-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 34px;
    margin-top: 52px;
}

.user-card {
    text-align: center;
}

.user-arc {
    position: relative;
    width: min(100%, 1100px);
    height: 430px;
    margin: 40px auto 0;
}

.user-arc-svg {
    width: 100%;
    height: 100%;
    display: block;
}

.user-arc-icon {
    position: absolute;
    width: 72px;
    height: 72px;
    display: grid;
    place-items: center;
    border-radius: 22px;
    background: #eceef2;
    color: #6a738a;
    font-size: 2rem;
    box-shadow: inset 0 0 0 2px rgba(110, 117, 135, 0.06);
}

.arc-icon-a {
    left: 150px;
    bottom: 102px;
}

.arc-icon-b {
    left: 390px;
    bottom: 238px;
}

.arc-icon-c {
    right: 390px;
    bottom: 238px;
}

.arc-icon-d {
    right: 150px;
    bottom: 102px;
}

.slide-cta .section-lead {
    max-width: 86%;
}

.cta-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    margin-top: 38px;
    border: 2px solid #cfd2da;
    border-radius: 12px;
    overflow: hidden;
    background: #e5e7ec;
}

.cta-card {
    min-height: 234px;
    padding: 28px 32px;
    background: #e5e7ec;
    border-right: 2px solid #cfd2da;
}

.cta-card:last-child {
    border-right: 0;
}

.slide-cta .button-row {
    margin-top: 38px;
}

@media (max-width: 1200px) {
    .slide-cover,
    .slide-why,
    .future-grid,
    .infrastructure-grid,
    .challenge-grid,
    .detail-grid,
    .impact-stats,
    .impact-notes,
    .user-grid,
    .feature-grid,
    .cta-grid {
        grid-template-columns: 1fr;
    }

    .slide-cover {
        min-height: auto;
    }

    .cover-media {
        min-height: 520px;
    }

    .impact-line {
        display: none;
    }

    .user-arc {
        display: none;
    }

    .process-flow {
        min-height: auto;
    }

    .process-wave {
        display: none;
    }

    .process-steps {
        width: 100%;
        height: auto;
        display: grid;
        gap: 16px;
    }

    .process-step {
        position: static;
        width: 100%;
        padding: 18px 20px;
        border-radius: 16px;
        background: #eef0f4;
        text-align: left;
    }

    .feature-grid,
    .impact-notes,
    .impact-stats,
    .user-grid,
    .detail-grid,
    .challenge-grid,
    .cta-grid {
        gap: 22px;
    }

    .cta-grid {
        border: 0;
        background: transparent;
    }

    .cta-card {
        border: 2px solid #cfd2da;
        border-radius: 12px;
    }
}

@media (max-width: 780px) {
    .slide {
        padding: 34px 18px;
    }

    .slide h2 {
        font-size: 2.55rem;
    }

    .cover-copy {
        padding: 42px 18px 28px;
    }

    .cover-copy h1 {
        max-width: none;
        font-size: 3rem;
    }

    .cover-lead,
    .section-lead,
    .challenge-card p,
    .infra-feature p,
    .detail-item p,
    .feature-item p,
    .why-item p,
    .impact-stat p,
    .impact-note p,
    .roadmap-item p,
    .user-card p,
    .cta-card p {
        font-size: 1rem;
    }

    .btn-main,
    .btn-outline {
        width: 100%;
    }

    .cover-media {
        min-height: 360px;
    }

    .infra-illustration-panel,
    .why-art {
        min-height: auto;
    }

    .pyramid {
        overflow: hidden;
    }

    .level-1 {
        width: 210px;
        height: 160px;
    }

    .level-2 {
        width: 300px;
        height: 180px;
    }

    .level-3 {
        width: 390px;
        height: 210px;
    }
}
</style>
